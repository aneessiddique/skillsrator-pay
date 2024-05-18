<?php

namespace App\Http\Controllers\Account;

use App\DashboardSettings;
use App\DataTables\Account\ManualInvoicesDataTable;
use App\Gateway;
use App\Http\Requests\Account;
use App\Http\Requests\Account\CreateManualInvoicesRequest;
use App\Http\Requests\Account\UpdateManualInvoicesRequest;
use App\Models\Account\ManualInvoices;
use Flash;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Notifications\ManualPaymentInvoice;
// use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Response;

class ManualInvoicesController extends Controller
{
    /**
     * Display a listing of the ManualInvoices.
     *
     * @param ManualInvoicesDataTable $manualInvoicesDataTable
     * @return Response
     */
    public function index(ManualInvoicesDataTable $manualInvoicesDataTable)
    {
        return $manualInvoicesDataTable->render('account.manual_invoices.index');
    }

    /**
     * Show the form for creating a new ManualInvoices.
     *
     * @return Response
     */
    public function create()
    {
        $gateways = Gateway::where('ec_pay_gateway_enabled', 1)->get()->pluck('gateway_name_url', 'ec_pay_gateway_url');
        $currency_rates = DashboardSettings::get()->pluck('value', 'key');
        foreach ($currency_rates as $key => $currency_rate) {
            $rates[$key] = $key . " - " . $currency_rate;
        }
        // dd($rates);
        return view('account.manual_invoices.create')->with(['gateways' => $gateways, 'currency_rates' => $rates]);
    }

    /**
     * Store a newly created ManualInvoices in storage.
     *
     * @param CreateManualInvoicesRequest $request
     *
     * @return Response
     */
    public function store(CreateManualInvoicesRequest $request)
    {
        $input = $request->all();
        // dd($input);

        $txn_reference = env('EC_PAY_APP_ID') . 'ref' . time();

        $input['txn_gateway_options'] = json_encode($input['txn_gateway_options']);
        $input['txn_customer_bill_order_id'] = 'ECP-MP-' . $txn_reference;
        $input['txn_platform_return_url'] = env('APP_URL') . '/invoice/payment/thankyou';
        $manualInvoices = ManualInvoices::create($input);

        $txn = new Transaction;
        $txn->txn_amount = $input['txn_amount'];
        $txn->txn_currency = isset($input["txn_currency"]) ? $input["txn_currency"] : 'PKR';
        $txn->txn_currency_rate = isset($input["txn_currency_rate"]) ? $input["txn_currency_rate"] : Null;
        $txn->txn_customer_id = $input["txn_customer_id"];
        $txn->txn_customer_name = $input["txn_customer_name"];
        $txn->txn_customer_email = $input["txn_customer_email"];
        $txn->txn_customer_mobile = $input["txn_customer_mobile"];
        $txn->txn_payment_type = $input["txn_payment_type"];
        $txn->txn_gateway_options = $input['txn_gateway_options'];
        $txn->txn_customer_bill_order_id = $input["txn_customer_bill_order_id"];
        $txn->txn_description = $input["txn_description"];
        $txn->txn_platform_return_url = $input['txn_platform_return_url'];
        $txn->customer_ip = "0.0.0.0";

        $txn->txn_ec_platform_id = env('EC_PAY_APP_ID');
        $txn->txn_ec_gateway_id = 0;
        $txn->txn_reference = $txn_reference;
        // $txn->txn_status = $request->txn_status;
        $txn->txn_request = json_encode($request->all());

        $txn->save();
        // dd($txn);
        // $this->send_email_manual_invoice($txn);

        Flash::success('Manual Invoices saved successfully.');

        return redirect(route('account.manual_invoices.index'));
    }

    public function send_email_manual_invoice($transaction)
    {
        // dd($transaction);
        $user = new User();
        $user->email = $transaction->txn_customer_email;
        $user->subject = "Payment for " . $transaction->txn_payment_type;
        $user->greeting = "Dear " . $transaction->txn_customer_name . ",";
        $user->details = $transaction->txn_description;
        $user->payment_url = env('APP_URL') . '/invoice/payment/' . $transaction->txn_reference;
        $user->footer = "Thank you";
        $user->bcc = ["aneessiddique21@gmail.com"];
        return $user->notify(new ManualPaymentInvoice());
    }

    public function manual_payment_transaction($id)
    {
        $txn = Transaction::where('txn_reference', $id)->first();
        if (!$txn) {
            $txn = Transaction::where('txn_customer_bill_order_id', $id)->first();
        }
        // dd($txn);
        $pid = env('EC_PAY_APP_ID');
        $key = env('EC_PAY_KEY');
        $cipher = "aes-128-gcm";
        $iv = hex2bin(env('EC_PAY_IV'));

        $data = array(
            "txn_amount" => $txn->txn_amount,
            "txn_currency" => $txn->txn_currency,
            "txn_currency_rate" => $txn->txn_currency_rate,
            "txn_gateway_options" => json_decode($txn->txn_gateway_options),
            "txn_customer_id" => $txn->txn_customer_id,
            "txn_customer_name" => $txn->txn_customer_name,
            "txn_customer_email" => $txn->txn_customer_email,
            "txn_customer_mobile" => $txn->txn_customer_mobile,
            "txn_payment_type" => $txn->txn_payment_type,
            "txn_customer_bill_order_id" => $txn->txn_customer_bill_order_id,
            // "txn_allow_multiple" => $txn->txn_allow_multiple,
            // "txn_expiry_datetime" => $txn->txn_expiry_datetime,
            "txn_description" => $txn->txn_description,
            // "customer_ip" => $txn->customer_ip,
            "txn_platform_return_url" => $txn->txn_platform_return_url,
        );
        $plaintext = http_build_query($data);

        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
            $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
        }
        // dd($pid, $ciphertext, $data);
        return view('account.manual_invoices.manual_payment_transaction')->with(['id' => $pid, 'data' => $ciphertext]);
    }

    public function manual_payment_thankyou(Request $request)
    {
        $manualInvoices = ManualInvoices::where('txn_customer_bill_order_id', $request->orderid)->first();
        $txn = Transaction::where('txn_customer_bill_order_id', $request->orderid)->first();
        // dd($manualInvoices, $txn);
        if ($manualInvoices && $txn && $manualInvoices->txn_status != $txn->txn_status) {
            $manualInvoices->txn_status = $txn->txn_status;
            $manualInvoices->save();
        }

        return view('account.manual_invoices.manual_payment_thankyou');
    }

    /**
     * Display the specified ManualInvoices.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $manualInvoices = ManualInvoices::find($id);
        $txn = Transaction::where('txn_customer_bill_order_id', $manualInvoices->txn_customer_bill_order_id)->first();
        // dd($manualInvoices, $txn);
        if ($txn && $manualInvoices->txn_status != $txn->txn_status) {
            $manualInvoices->txn_status = $txn->txn_status;
            $manualInvoices->save();
        }

        if (empty($manualInvoices)) {
            Flash::error('Manual Invoices not found');

            return redirect(route('account.manual_invoices.index'));
        }

        return view('account.manual_invoices.show')->with('manualInvoices', $manualInvoices);
    }

    /**
     * Show the form for editing the specified ManualInvoices.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var ManualInvoices $manualInvoices */
        $manualInvoices = ManualInvoices::find($id);
        $gateways = Gateway::where('ec_pay_gateway_enabled', 1)->get()->pluck('gateway_name_url', 'ec_pay_gateway_url');

        $currency_rates = DashboardSettings::get()->pluck('value', 'key');
        foreach ($currency_rates as $key => $currency_rate) {
            $rates[$key] = $key . " - " . $currency_rate;
        }

        if (empty($manualInvoices)) {
            Flash::error('Manual Invoices not found');

            return redirect(route('account.manual_invoices.index'));
        }

        return view('account.manual_invoices.edit')->with(['manualInvoices' => $manualInvoices, 'gateways' => $gateways, 'currency_rates' => $rates]);
    }

    /**
     * Update the specified ManualInvoices in storage.
     *
     * @param int $id
     * @param UpdateManualInvoicesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateManualInvoicesRequest $request)
    {
        /** @var ManualInvoices $manualInvoices */
        $manualInvoices = ManualInvoices::find($id);

        if (empty($manualInvoices)) {
            Flash::error('Manual Invoices not found');

            return redirect(route('account.manual_invoices.index'));
        }

        $txn = Transaction::where('txn_customer_bill_order_id', $manualInvoices->txn_customer_bill_order_id)->first();

        if ($txn->txn_status == 'draft') {
            $input = $request->all();
            if (isset($input['txn_gateway_options'])) {
                $input['txn_gateway_options'] = json_encode($input['txn_gateway_options']);
            }

            $manualInvoices->fill($input);
            $manualInvoices->save();

            $txn->fill($input);
            $txn->save();

            Flash::success('Manual Invoices updated successfully.');
        } else {
            Flash::error('Invoice Can\'t be edited.');
        }


        return redirect(route('account.manual_invoices.index'));
    }

    /**
     * Remove the specified ManualInvoices from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ManualInvoices $manualInvoices */
        $manualInvoices = ManualInvoices::find($id);

        if (empty($manualInvoices)) {
            Flash::error('Manual Invoices not found');

            return redirect(route('account.manual_invoices.index'));
        }

        $manualInvoices->delete();

        Flash::success('Manual Invoices deleted successfully.');

        return redirect(route('account.manual_invoices.index'));
    }
}
