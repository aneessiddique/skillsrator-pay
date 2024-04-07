<?php

namespace App\Http\Controllers\Account;

use App\ApiKey;
use App\DataTables\Account\Kuickpay2TransactionsDataTable;
use App\DataTables\Account\KuickpayTransactionsDataTable;
use App\DataTables\Account\transactionsDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Transaction;
use App\Gateway;
use App\IPNLogs;
use App\Notifications\DepositSlipReceived;
use App\Notifications\ProgramPaymentRejected;
use App\TransactionDepositSlip;
use App\TransactionRefundRequest;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class TransactionsController extends Controller
{
    // public function index()
    // {
    //     // abort_if(Gate::denies('Transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $transactions = Transaction::all();
    //     // dd($transactions);
    //     return view('account.transactions.index', compact('transactions'));
    // }

    public function index(transactionsDataTable $transactionsDataTable)
    {
        return $transactionsDataTable->render('account.transactions.index');
    }

    public function kuickpay_index(KuickpayTransactionsDataTable $transactionsDataTable)
    {
        return $transactionsDataTable->render('account.transactions.index');
    }

    public function kuickpay2_index(Kuickpay2TransactionsDataTable $transactionsDataTable)
    {
        return $transactionsDataTable->render('account.transactions.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('Transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $roles = Role::all()->pluck('title', 'id');

        return view('account.transactions.create', compact('roles'));
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = Transaction::create($request->all());
        // $transaction->roles()->sync($request->input('roles', []));

        return redirect()->route('account.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        // abort_if(Gate::denies('Transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $roles = Role::all()->pluck('title', 'id');

        // $transaction->load('roles');

        return view('account.transactions.edit', compact('transaction'));
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->all());
        // $transaction->roles()->sync($request->input('roles', []));

        return redirect()->route('account.transactions.index');
    }

    public function show(Transaction $transaction)
    {
        // abort_if(Gate::denies('Transaction_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund_request_exists = TransactionRefundRequest::where(['approved' => 0, 'rejected' =>  0, 'txn_id' => $transaction->id])->first();

        $refund_requests = TransactionRefundRequest::where(['txn_id' => $transaction->id])->get();
        $deposit_slips = TransactionDepositSlip::where(['txn_id' => $transaction->id])->get();

        return view('account.transactions.show', compact(['transaction', 'refund_request_exists', 'refund_requests', 'deposit_slips']));
    }

    public function show_by_orderid($id)
    {
        $transaction = Transaction::where('txn_customer_bill_order_id', $id)
            // ->where('txn_status', '<>', 'draft')
            ->first();

        return view('account.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        // abort_if(Gate::denies('Transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $transaction->delete();

        return back();
    }

    public function txnDelete($id)
    {
        // dd(Transaction::findOrFail($id));
        $txn = Transaction::where('txn_customer_bill_order_id', $id)
            ->where('txn_status', '<>', 'completed')
            ->delete();

        return response()->json(['result' => $txn]);
    }

    public function txnExpire($id)
    {
        // dd(Transaction::findOrFail($id));
        $txn = Transaction::where('txn_customer_bill_order_id', $id)
            ->where('txn_status', '<>', 'completed')
            ->first();

        $txn->txn_expiry_datetime = date('Y-m-d H:i:s', strtotime('-2 days'));
        $txn->save();

        return response()->json(['result' => $txn]);
    }

    public function voucherExpire($voucher)
    {
        // dd(Transaction::findOrFail($id));
        $txn = Transaction::where('txn_response_ref', $voucher)
            ->first();

        if($txn){
            $txn->txn_expiry_datetime = date('Y-m-d H:i:s', strtotime('-2 days'));
            $txn->save();
        }

        return response()->json(['result' => $txn]);
    }

    public function update_dollar_rate(Request $request)
    {
        // dd(Transaction::findOrFail($id));
        $txns = Transaction::where('txn_description', 'LIKE', '%Incubation Subscription%')
            ->where('created_at', '<', '2022-05-31')
            ->get();

        foreach ($txns as $key => $txn) {
            // dump($txn);
            $txn->txn_currency_rate = 'Dollar_Rates_2';
            $txn->save();
        }

        // return response()->json(['result' => $txn]);
    }

    public function txnStatus($id)
    {
        // dd(Transaction::findOrFail($id));
        $txn = Transaction::where('txn_customer_bill_order_id', $id)
            ->where('txn_status', '<>', 'draft')
            ->get();
        if (count($txn) < 1) {
            $txn = Transaction::where('txn_customer_bill_order_id', $id)->get();
        }
        // dd($txn);

        // Reconcile from stripe
        // $reconcileRequest = new \Illuminate\Http\Request();
        // $reconcileRequest->setMethod('POST');
        // $reconcileRequest->request->add(['txn_customer_bill_order_id' => $txn->txn_customer_bill_order_id]);
        // $txn = $this->txnReconcile($reconcileRequest);

        return new TransactionResource($txn);
    }

    public function txnReconcile(Request $request)
    {
        // dd($request);
        if (isset($request->txn_id)) {
            $txn = Transaction::findOrFail($request->txn_id);
        } elseif (isset($request->txn_customer_bill_order_id)) {
            $txn = Transaction::where('txn_customer_bill_order_id', $request->txn_customer_bill_order_id)->first();
        } else {
            return response()->json(['error' => 'Missing required parameters']);
        }

        $payed_on_gateway = Gateway::find($txn->txn_ec_gateway_id);

        if ($payed_on_gateway) {
            $payed_on_gateway = $payed_on_gateway->ec_pay_gateway_url;
        } else {
            $payed_on_gateway = '';
        }
        // dd($payed_on_gateway,$txn);
        if ($payed_on_gateway == "stripe" || $payed_on_gateway == "stripep") {
            $txn_response_ref = trim($txn->txn_response_ref);
            if (strlen($txn_response_ref) > 0) {
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $paymentIntent = $stripe->paymentIntents->retrieve(
                    $txn_response_ref,
                    []
                );
            }
            // dd($paymentIntent);
            if (isset($paymentIntent->charges->data)) {
                // dd($paymentIntent);
                if ($paymentIntent->charges->data[0]->paid == true) {
                    $txn->txn_status = 'completed';
                }

                if ($paymentIntent->charges->data[0]->refunded == true) {
                    $txn->txn_status = 'refunded';
                }

                if ($paymentIntent->charges->data[0]->disputed == true) {
                    $disputed_txn = $stripe->disputes->retrieve(
                        $paymentIntent->charges->data[0]->dispute,
                        []
                    );
                    // dd($disputed_txn);
                    if ($disputed_txn->status == 'lost') {
                        $txn->txn_status = 'refunded';
                    }
                }
            }

            $txn->txn_response = json_encode($paymentIntent) . (isset($disputed_txn) ? json_encode($disputed_txn) : '');
            $txn->save();
        } elseif ($payed_on_gateway == "kuickpay") {
            $this->kuickpay_reconcile($txn);
        }

        if (isset($request->txn_id)) {
            return redirect()->route('account.transactions.show', [$request->txn_id]);
        } else {
            return new TransactionResource($txn);
        }
    }

    public function kuickpay_reconcile(&$txn)
    {
        try {
            // dd(var_dump( get_cfg_var('cfg_file_path') ),var_dump(extension_loaded('soap')), phpinfo());
            ini_set("soap.wsdl_cache_enabled", 0);
            $consumerNumber = generate_txn_reference($txn->txn_reference); //"0191029001642861570";
            // $atservices_wsdl = "https://app2.kuickpay.com/KuickpayCoreAPITest/API.asmx?WSDL";
            $atservices_wsdl = "https://app.kuickpay.com/KuickpayCoreAPI/API.asmx?WSDL";
            $atservices_client = new \SoapClient($atservices_wsdl);
            $funcc = $atservices_client->__getFunctions();
            // $smsparam = array('userName' => "EXTREMECOMMERCEADMIN", 'password' => "Admin@12345", 'consumerNumber' => $consumerNumber);
            // $smsparam = array('userName' => "kuickpay", 'password' => "payx", 'consumerNumber' => $consumerNumber);
            $smsparam = array('userName' => "EXTREMECOMMERCEADMIN1", 'password' => "Admin@12345", 'consumerNumber' => $consumerNumber);
            $response = $atservices_client->BillPaymentInquiry($smsparam);

            $voucherResult = explode(',', $response->BillPaymentInquiryResult);
            if ($voucherResult[0] == '00') {
                $txn->txn_status = 'completed';
            }
            // dd($response);
            $txn->txn_response = json_encode($response);
            $txn->save();
        }

        //catch exception
        catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
        return $response;
    }

    public function createMultipleTxn(Request $request)
    {
        if(isset($request->voucher_expire) && $request->voucher_expire != 0){
            $expired = $this->voucherExpire($request->voucher_expire);
        }
        $api_key = ApiKey::find($request->id);
        $ciphertext = substr($request->data, 0, -32); //"4TtIbf2gBuH5tRL8rszxP6MdMtq53kOeG6oR3Xo1D0e1t1TOYmPW70Pep5LsNppWe2xcidlzF+1X2kGxboVuVPNEA63GBGnYXF41vtgiiGZpTDm9WffCUopQRPOWS2nl1DHS84t4gwTSalWOqRcc8pLr88wRn1lkmPYDXQpAeOhc06zQQ/mJxZtqrVBW/ZGOUSs6EUFIKX15eazA+3+eyUaftnEPU6XRSpxDPftS1lolLAs+KZ502hdozAA5VJrw6udNDzOilY4IZWVEMdykygGtAaAGzrL7eupQwxn6LWDA/l0Q9Z39j5HX2FLESNkvOkyDvp88IcbZdTAFXgKwk35TpJvgIP+wf+hdOiO0TqiY5lFs6xjBAej+ujPqs3zGce/3thceSzBqXT2vSjzCHww/i0/Qbd2wiJvo/m4sGbTnAKw9AaEQqGAuZHvR+fyrRRRHRTF/Q4yeIVRIIatv7vgoRKxYBdWT6bO5kx+roMWZLCW7RXP595ma2FPaEzJSt8cyqgPjZEbESSyHuA0R0g0M8Fyfelfcr934VdhKN9Vjb+1ixGb2Rg==";
        $cipher = "aes-128-gcm";
        $key = $api_key->ec_pay_api_key; //'jbghghjghggjhghjg';
        $iv = $api_key->ec_pay_api_iv; //"c948deeaaae2207b70ce840c";
        $tag = substr($request->data, -32); //"ab36816b7ab7fe5d37967ec430783a3a";
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, hex2bin($iv), hex2bin($tag));

        if (!$original_plaintext) {
            return redirect()->back()->with('error', 'Invalid data');
        }

        parse_str($original_plaintext, $decoded_array);


        $installments = $decoded_array["installments"];
        $txn_amount = $decoded_array["txn_amount"] / $installments;

        for ($i = 0; $i < $installments; $i++) {
            $txn = new Transaction;
            $txn->txn_amount = $txn_amount;
            $txn->txn_currency = isset($decoded_array["txn_currency"]) ? $decoded_array["txn_currency"] : 'PKR';
            $txn->txn_currency_rate = isset($decoded_array['txn_currency_rate']) ? $decoded_array['txn_currency_rate'] : Null;
            $txn->txn_customer_id = $decoded_array["txn_customer_id"];
            $txn->txn_customer_name = $decoded_array["txn_customer_name"];
            $txn->txn_customer_email = $decoded_array["txn_customer_email"];
            $txn->txn_customer_mobile = $decoded_array["txn_customer_mobile"];
            $txn->txn_payment_type = $decoded_array["txn_payment_type"];
            $txn->txn_gateway_options = isset($decoded_array['txn_gateway_options']) ? json_encode($decoded_array['txn_gateway_options']) : '';
            $txn->txn_customer_bill_order_id = $decoded_array["txn_customer_bill_order_id"];
            $txn->txn_description = $decoded_array["txn_description"];
            $txn->txn_platform_return_url = $decoded_array["txn_platform_return_url"];
            $txn->customer_ip = $decoded_array["customer_ip"];
            $txn->txn_expiry_datetime = isset($decoded_array['txn_expiry_datetime']) ? $decoded_array['txn_expiry_datetime'] : Date('Y-m-d H:i:s', strtotime('+10 days')); // should be "Y-m-d H:i" YYYY-MM-DD 24:00

            $txn->txn_ec_platform_id = $api_key->id;
            $txn->txn_ec_gateway_id = 0;
            $txn->txn_reference = $api_key->id . 'ref' . (time() + $i);
            // $txn->txn_status = $request->txn_status;
            $txn->txn_request = json_encode($decoded_array);

            $txn->save();
        }
        $txns = Transaction::where('txn_customer_bill_order_id', $decoded_array["txn_customer_bill_order_id"])->get();
        // $prefix = config('gateways.kuickpay.token_prefix');
        foreach ($txns as $txn) {
            $vouchers[] = generate_txn_reference($txn->txn_reference);
        }

        $session = $request->getRequestUri();
        $ipn_logs = new IPNLogs();
        $ipn_logs->ipn_gateway = 'kuickpay';
        $ipn_logs->ipn_response = json_encode($session). json_encode($vouchers);
        $ipn_logs->save();

        return response()->json([
            'vouchers' => $vouchers
        ]);
    }

    public function trigger_manual_ipn(Request $request)
    {
        if (isset($request->txn_id)) {
            $txn = Transaction::findOrFail($request->txn_id);
            var_dump(notify_platform_return_url($txn));
            var_dump($txn);
            // dd($txn, notify_platform_return_url($txn));
            return redirect()->back();
        }
    }

    public function massDestroy(MassDestroyTransactionRequest $request)
    {
        // Transaction::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function deposit_slip_transaction_index()
    {
        $deposit_slips = TransactionDepositSlip::with('transaction')
            ->where('approved', 0)
            ->where('rejected', 0)
            ->get();
        // dd($deposit_slips);
        return view('account.transactions.deposit_slips', compact('deposit_slips'));
    }

    public function deposit_slip_transaction_show($id)
    {
        $deposit_slip = TransactionDepositSlip::with(['transaction', 'gateway', 'deposit_slip_fields_data.deposit_slip_fields'])->find($id);
        // dd($deposit_slip);
        return view('account.transactions.deposit_slip_show', compact('deposit_slip'));
    }

    public function deposit_slip_transaction_approve($id, Request $request)
    {
        $transaction_slip = TransactionDepositSlip::find($id);
        $transaction_slip->approved = 1;
        $transaction_slip->approved_by = auth()->user()->id;
        $transaction_slip->save();
        // dd($transaction_slip);

        $transaction = Transaction::find($transaction_slip->txn_id);
        $transaction->txn_ec_gateway_id = $transaction_slip->gateway_id;
        $transaction->txn_status = 'completed';
        $transaction->save();
        // dd($transaction->txn_platform_return_url);

        $user = new User();;
        $user->subject = "Payment Proof verified Successfully - " . $transaction->txn_customer_bill_order_id;
        $user->greeting = "Dear " . $transaction->txn_customer_name . ",";
        $user->message1 = "Your Payment Proof has been verified successfully.";
        $user->message2 = "Payment Proof ref#: " . $transaction->txn_customer_bill_order_id;
        $user->thankyou_message = "Thank you";
        $user->email = $transaction->txn_customer_email;
        $user->bcc = "manual.invoice.sales@ec.com.pk";
        $user->notify(new DepositSlipReceived());

        $response = notify_platform_return_url($transaction);
        if (isset($request->mode) && $request->mode == 'debug') {
            echo $response;
        }
        // dd('end response');
        return redirect()->route('account.transactions.deposit_slip_transaction_index');
    }

    public function deposit_slip_transaction_reject(Request $request)
    {
        // dd($request->all());
        $transaction_slip = TransactionDepositSlip::find($request->deposit_slip_id);
        $transaction_slip->rejected = 1;
        $transaction_slip->rejected_by = auth()->user()->id;
        $transaction_slip->reject_reason = $request->reject_reason;
        $transaction_slip->save();
        // dd($transaction_slip);

        // $response = $this->notify_platform_return_url($transaction);
        $transaction = Transaction::find($transaction_slip->txn_id);
        $pos = strpos($transaction->txn_customer_bill_order_id, '-ECP-'); // invoiceid-ECP-userid-regid-
        $invoice_id = substr($transaction->txn_customer_bill_order_id, 0, $pos);

        $user = new User();
        $user->subject = "Payment Proof Rejected";
        $user->greeting = "Dear " . $transaction->txn_customer_name . ",";
        $user->message1 = "Your payment proof is not accepted, Please find below the comments from the team..";
        $user->message2 = $request->reject_reason;
        $user->message3 = "Please click below to submit payment.";
        $user->payment_url = env('WEB_URL') . 'ec-programs/payment/' . $invoice_id;
        $user->thankyou_message = "Thank you";
        $user->email = $transaction->txn_customer_email;
        $user->bcc = "sales@ec.com.pk";
        $user->notify(new ProgramPaymentRejected());

        return redirect()->route('account.transactions.deposit_slip_transaction_index');
    }

    // function notify_platform_return_url($transaction)
    // {

    //     $ch = curl_init();

    //     $fields = array(
    //         'code' => 200,
    //         'status' => $transaction->txn_status,
    //         'amount' => $transaction->txn_amount,
    //         // 'message' => $Message,
    //         'orderid' => $transaction->txn_customer_bill_order_id,
    //         'tnxid' => $transaction->id
    //     );
    //     $postvars = '';
    //     foreach ($fields as $key => $value) {
    //         $postvars .= $key . "=" . $value . "&";
    //     }

    //     curl_setopt($ch, CURLOPT_URL, $transaction->txn_platform_return_url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    //     $response = curl_exec($ch);
    //     // print "curl response is:" . $response;
    //     curl_close($ch);
    //     return $response;
    // }

    public function refund(Request $request)
    {
        $refund_request_exists = TransactionRefundRequest::where(['approved' => 0, 'rejected' =>  0, 'txn_id' => $request->txn_id])->first();
        if ($refund_request_exists) {
            return redirect()->back()->with('error', 'Refund request already placed.');
        }

        if (isset($request->txn_id)) {
            $txn = Transaction::find($request->txn_id);
        }

        if (isset($request->txn_customer_bill_order_id)) {
            $txn = Transaction::where('txn_customer_bill_order_id', $request->txn_customer_bill_order_id)
                ->where('txn_status', 'completed')
                ->first();
        }

        $refund_request = new TransactionRefundRequest();
        $refund_request->fill([
            'txn_id' => $request->txn_id,
            'gateway_id' => $txn->txn_ec_gateway_id,
        ]);
        $refund_request->save();

        return back();
    }

    public function stripe_refund($transaction)
    {
        $payment_intent = $transaction->txn_response_ref;
        if (substr($payment_intent, 0, 3) == 'pi_') {

            if ($transaction->gateway == 'stripem' || $transaction->gateway == 'stripemp') {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_MY'));
            } elseif ($transaction->gateway == 'stripeu' || $transaction->gateway == 'stripeup') {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_US'));
            } else {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            }

            $refund = $stripe->refunds->create(['payment_intent' => $payment_intent]);
            // dd($refund);

            return redirect()->route('account.transactions.transaction_refund_request_index');
        }
        // $payment_intent_key = strpos($transaction->txn_response, 'payment_intent');
        // if ($payment_intent_key > 0) {
        //     // $payment_intent = substr($transaction->txn_response, $payment_intent_key);
        //     $txn_response = json_decode($transaction->txn_response, true);
        //     if ($txn_response['data'] && $txn_response['data']['object'] && $txn_response['data']['object']['payment_intent']) {
        //         $payment_intent = $txn_response['data']['object']['payment_intent'];

        //         $stripe = new \Stripe\StripeClient(
        //             env('STRIPE_SECRET')
        //         );
        //         $refund = $stripe->refunds->create(['payment_intent' => $payment_intent]);

        //         // dd($txn_response, $payment_intent, $refund, $stripe);
        //         return redirect()->route('transactions.deposit_slip_transaction_index');
        //     }
        // }
    }

    public function transaction_refund_request_index()
    {
        $refund_requests = TransactionRefundRequest::with('transaction')
            ->where('approved', 0)
            ->where('rejected', 0)
            ->get();
        // dd($txn_refund_requests);
        return view('account.transactions.refund_transaction_list', compact('refund_requests'));
    }

    public function transaction_refund_request_show($id)
    {
        $txn_refund_request = TransactionRefundRequest::with('transaction')->find($id);
        // dd($transactions);
        return view('account.transactions.refund_request_show', compact('txn_refund_request'));
    }

    public function transaction_refund_request_approve(Request $request)
    {
        $refund_request = TransactionRefundRequest::find($request->refund_request_id);
        $refund_request->approved = 1;
        $refund_request->approved_by = auth()->user()->id;
        $refund_request->approved_reason = $request->approved_reason;
        $refund_request->save();
        // dd($refund_request);

        $transaction = Transaction::find($refund_request->txn_id);
        $gateway_used = Gateway::find($transaction->txn_ec_gateway_id);

        if ($gateway_used && (
                $gateway_used->ec_pay_gateway_url == 'stripe' ||
                $gateway_used->ec_pay_gateway_url == 'stripep' ||
                $gateway_used->ec_pay_gateway_url == 'stripem' ||
                $gateway_used->ec_pay_gateway_url == 'stripemp' ||
                $gateway_used->ec_pay_gateway_url == 'stripeu' ||
                $gateway_used->ec_pay_gateway_url == 'stripeup'
            )) {
            $response = $this->stripe_refund($transaction);
            // dd($response);
        }

        if ($gateway_used && $gateway_used->ec_pay_gateway_url == 'kuickpay') {
            $transaction->txn_ec_gateway_id = $refund_request->gateway_id;
            $transaction->txn_status = 'refunded';
            $transaction->save();
        }

        // echo $response;
        // dd('end response');
        return redirect()->route('account.transactions.transaction_refund_request_index');
    }

    public function transaction_refund_request_reject(Request $request)
    {
        // dd($request->all());
        $txn_refund_request = TransactionRefundRequest::find($request->refund_request_id);
        $txn_refund_request->rejected = 1;
        $txn_refund_request->rejected_by = auth()->user()->id;
        $txn_refund_request->reject_reason = $request->reject_reason;
        $txn_refund_request->save();
        // dd($txn_refund_request);

        // $response = $this->notify_platform_return_url($transaction);
        // $transaction = Transaction::find($txn_refund_request->txn_id);
        // $pos = strpos($transaction->txn_customer_bill_order_id, '-ECP-'); // invoiceid-ECP-userid-regid-
        // $invoice_id = substr($transaction->txn_customer_bill_order_id, 0, $pos);

        // $user = new User();
        // $user->subject = "Payment Proof Rejected";
        // $user->greeting = "Dear ".$transaction->txn_customer_name.",";
        // $user->message1 = "Your payment proof is not accepted, Please find below the comments from the team..";
        // $user->message2 = $request->reject_reason;
        // $user->message3 = "Please click below to submit payment.";
        // $user->payment_url = env('WEB_URL').'ec-programs/payment/'.$invoice_id;
        // $user->thankyou_message = "Thank you";
        // $user->email = $transaction->txn_customer_email;
        // $user->bcc("sales@ec.com.pk");
        // $user->notify(new ProgramPaymentRejected());

        return redirect()->route('account.transactions.transaction_refund_request_index');
    }

    public function jazz_refund(Request $request)
    {
        // abort_if(Gate::denies('Transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transaction = Transaction::find($request->txn_id);
        $jazz_merchantid = config('gateways.jazz.merchantid');
        $jazz_password = config('gateways.jazz.password');
        $jazz_integeritysalt = config('gateways.jazz.integeritysalt');

        // echo $phpstring = $jazz_integeritysalt."&".(20000*100)."&".$jazz_merchantid."&".$jazz_password."&PKR&1ref1629119685";
        $phpstring = $jazz_integeritysalt . "&" . ($transaction->txn_amount * 100) . "&" . $jazz_merchantid . "&" . $jazz_password . "&PKR&" . $transaction->txn_reference;
        // echo "<br>";
        $phphash = hash_hmac("sha256", $phpstring,  $jazz_integeritysalt);

        $data = [
            'pp_TxnRefNo' => $transaction->txn_reference, //'1ref1629119685',
            'pp_Amount' => $transaction->txn_amount * 100, //'2000000',
            'pp_TxnCurrency' => "PKR",
            'pp_MerchantID' => $jazz_merchantid,
            'pp_Password' => $jazz_password,
            'pp_SecureHash' => $phphash
        ];
        // dd($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox.jazzcash.com.pk/ApplicationAPI/API/authorize/Refund",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        dd($response);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            print_r(json_decode($response));
        }
    }

    public function fix_bc2022_kyc_data(Request $request)
    {
        $ecp_transactions = Transaction::where('txn_customer_bill_order_id', 'LIKE', "%-ECP-%")->get();
        // dd($ecp_transactions);
        foreach ($ecp_transactions as $key => $ecp_transaction) {
            echo $txn_customer_bill_order_id = $ecp_transaction->txn_customer_bill_order_id;
            echo " <=> ";
            $bc2022_reg_id = explode('-', $txn_customer_bill_order_id);
            echo substr($bc2022_reg_id[3], 1);

            $bc2022_reg = DB::connection('mysql2')->table('ec_programs_registration')->find(substr($bc2022_reg_id[3], 1));
            $bc2022_reg_kyc = DB::connection('mysql2')->table('ec_programs_registration_kyc')->where('registration_id', substr($bc2022_reg_id[3], 1))->first();
            // var_dump($bc2022_reg_kyc);
            if ($bc2022_reg) {
                if ($ecp_transaction->txn_customer_name != $bc2022_reg_kyc->full_name) {
                    echo "<br />========Mismatch==========";
                }
                echo "<br />";
                echo $ecp_transaction->txn_customer_id;
                echo " <=> ";
                echo $bc2022_reg->user_id;
                echo "<br />";
                echo $ecp_transaction->txn_customer_name;
                echo " <=> ";
                echo $bc2022_reg_kyc->full_name;
                echo "<br />";
                echo $ecp_transaction->txn_customer_email;
                echo " <=> ";
                echo $bc2022_reg_kyc->email;
                echo "<br />";
                echo $ecp_transaction->txn_customer_mobile;
                echo " <=> ";
                echo $bc2022_reg_kyc->mobile_whatsapp_number;
                echo "<br />";
                if ($ecp_transaction->txn_customer_name !== $bc2022_reg_kyc->full_name) {
                    echo "==================<br />";
                }
                if ($request->mode && $request->mode == 'update') {
                    $ecp_transaction->txn_customer_id = $bc2022_reg->user_id;
                    $ecp_transaction->txn_customer_name = $bc2022_reg_kyc->full_name;
                    $ecp_transaction->txn_customer_email = $bc2022_reg_kyc->email;
                    $ecp_transaction->txn_customer_mobile = $bc2022_reg_kyc->mobile_whatsapp_number;
                    $ecp_transaction->save();
                }
            } else {
                echo "<br />Registration data not found. <br />";
            }
        }
    }
}
