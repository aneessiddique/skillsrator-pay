<?php

namespace App\Http\Controllers;

use App\ApiKey;
use App\DashboardSettings;
use App\Gateway;
use App\Helpers\StorageMedia;
use App\IPNLogs;
use App\Models\Account\TransactionDepositSlipFieldsData;
use App\Models\Transaction;
use App\Notifications\DepositSlipReceived;
use App\Notifications\PaymentReceipt;
use App\TransactionDepositSlip;
use App\User;
use Illuminate\Http\Request;
use Stripe;
use PDF;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction as paypaltransaction;

use Log;

class gatewayController extends Controller
{
    use StorageMedia;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //Track exection time start
        $start = microtime(true);

        try {
            // $api_key = ApiKey::where('id', $request->id)->get();
            $api_key = ApiKey::find($request->id);
            // dd($api_key);
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

            // retrive txn if multiple is not set.
            $txn_exists = Transaction::where('txn_customer_bill_order_id', $decoded_array['txn_customer_bill_order_id'])->first();

            if ($txn_exists && (strtotime($txn_exists->txn_expiry_datetime) < strtotime(date("Y-m-d")))) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
                return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn_exists->txn_expiry_datetime), 'time_now' => strtotime(date("Y-m-d"))]);
            }

            if ($txn_exists && $txn_exists->txn_status == "completed") { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
                return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
            }

            if (!isset($decoded_array['txn_allow_multiple']) && $txn_exists) {
                $txn = $txn_exists;
                if(isset($decoded_array['txn_gateway_options'])){
                    $txn->txn_gateway_options = json_encode($decoded_array['txn_gateway_options']);
                }
                // $txn->txn_request = json_encode($decoded_array).$txn->txn_request;
                $txn->save();
            } else {
                $txn = new Transaction;
                $txn->txn_amount = $decoded_array['txn_amount'];
                $txn->txn_currency = isset($decoded_array['txn_currency']) ? $decoded_array['txn_currency'] : 'PKR';
                $txn->txn_currency_rate = isset($decoded_array['txn_currency_rate']) ? $decoded_array['txn_currency_rate'] : Null;
                $txn->txn_customer_id = $decoded_array['txn_customer_id'];
                $txn->txn_customer_name = $decoded_array['txn_customer_name'];
                $txn->txn_customer_email = $decoded_array['txn_customer_email'];
                $txn->txn_customer_mobile = $decoded_array['txn_customer_mobile'];
                $txn->txn_payment_type = $decoded_array['txn_payment_type'];
                // dd($decoded_array['txn_gateway_options']);
                $txn->txn_gateway_options = isset($decoded_array['txn_gateway_options']) ? json_encode($decoded_array['txn_gateway_options']) : '';
                $txn->txn_customer_bill_order_id = $decoded_array['txn_customer_bill_order_id'];
                $txn->txn_description = $decoded_array['txn_description'] . " (Order ID " . $decoded_array['txn_customer_bill_order_id'] . ")";
                $txn->txn_platform_return_url = $decoded_array['txn_platform_return_url'];
                $txn->customer_ip = $decoded_array['customer_ip'];
                $txn->txn_expiry_datetime = isset($decoded_array['txn_expiry_datetime']) ? $decoded_array['txn_expiry_datetime'] : Date('Y-m-d H:i:s', strtotime('+2 days')); // should be "Y-m-d H:i" YYYY-MM-DD 24:00

                $txn->txn_ec_platform_id = $api_key->id;
                $txn->txn_ec_gateway_id = 0;
                $txn->txn_reference = $api_key->id . 'ref' . time();
                // $txn->txn_status = $decoded_array['txn_status'];
                $txn->txn_request = json_encode($decoded_array);

                $txn->save();
            }

            // For Free registrations
            if ($txn->txn_amount == 0) {
                $txn->txn_response_ref = 'ec-free-promo';
                $txn->txn_status = 'completed';
                $txn->txn_response = json_encode(['txn_status' => 'Free offer from Extreme Commerce.']);
                $txn->save();

                return redirect()->away(
                    $txn->txn_platform_return_url .
                        '?status=' . $txn->txn_status .
                        '&orderid=' . $txn->txn_customer_bill_order_id .
                        '&amount=' . $txn->txn_amount .
                        '&response_ref=ec-free-promo'
                );
            }

            $deposit_slip_exists = TransactionDepositSlip::where(['approved' => 0, 'rejected' =>  0, 'txn_id' => $txn->id])->first();
            if ($deposit_slip_exists) {
                return view('layouts.transaction_verification', ['deposit_slip_exists' => $deposit_slip_exists]);
            }

            $currency_rates = DashboardSettings::get()->keyBy('key');
            $usd_rate = $currency_rates['Dollar_Rates']->value;
            $cad_rate = $currency_rates['CAD_rates']->value;
            $gbp_rate = $currency_rates['GBP_rates']->value;
            $aud_rate = $currency_rates['AUD_rates']->value;
            $sar_rate = $currency_rates['SAR_rates']->value;

            if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
                $usd_rate = $currency_rates[$txn->txn_currency_rate]->value;
            }

            $base_currency = 'PKR';
            // if ($txn->txn_currency != 'PKR') {
                $base_currency = $txn->txn_currency;
                $base_amount = $txn->txn_amount;
            // } else {
            //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
            //         $base_currency = $txn->txn_currency;
            //         $base_amount = $txn->txn_amount;
            //     } else {
            //         $base_currency = 'USD';
            //         $base_amount = $txn->txn_amount / $usd_rate;
            //     }
            // }

            if ($txn->txn_currency == 'PKR') {
                $pkr_amount = $txn->txn_amount;
            } elseif ($txn->txn_currency == 'USD') {
                $pkr_amount = $usd_rate * $txn->txn_amount;
            } elseif ($txn->txn_currency == 'CAD') {
                $pkr_amount = $cad_rate * $txn->txn_amount;
            } elseif ($txn->txn_currency == 'GBP') {
                $pkr_amount = $gbp_rate * $txn->txn_amount;
            } elseif ($txn->txn_currency == 'AUD') {
                $pkr_amount = $aud_rate * $txn->txn_amount;
            } elseif ($txn->txn_currency == 'SAR') {
                $pkr_amount = $sar_rate * $txn->txn_amount;
            }


            // $gateways = Gateway::where(['ec_pay_gateway_enabled' => 1, 'ec_pay_gateway_currency' => $txn->txn_currency])->get();
            $gateways = Gateway::where(['ec_pay_gateway_enabled' => 1]);

            if (isset($decoded_array['txn_gateway_options'])) {
                $gateways = $gateways->whereIn('ec_pay_gateway_url', $decoded_array['txn_gateway_options']);
            }

            $gateways = $gateways->get()->sortBy('ec_pay_gateway_sort');

            if (isset($request->mode) && $request->mode == 'debug') {
                dd($decoded_array, $gateways);
            }
        } catch (Exception $e) {
            return response($e->getMessage());
        }

        //Track exection time end
        $time_elapsed_secs = microtime(true) - $start;

        return view('layouts.gateways2', [
            "data" => $txn,
            "auto_click" => true,
            "gateways" => $gateways,
            "currency_rates" => $currency_rates,
            "base_currency" => $base_currency,
            "base_amount" => $base_amount,
            "pkr_amount" => $pkr_amount,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deposit_slip_create_checkout($id, Request $request)
    {
        $txn = Transaction::find($id);

        if ($txn && $txn->txn_status == "completed") {
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        $deposit_slip_exists = TransactionDepositSlip::where(['approved' => 0, 'rejected' =>  0, 'txn_id' => $txn->id])->first();
        if ($deposit_slip_exists) {
            return view('layouts.transaction_verification', ['deposit_slip_exists' => $deposit_slip_exists]);
        }

        // dd($request->segment(1));
        $gateway = Gateway::with('deposit_slip_fields')
            ->where('ec_pay_gateway_url', $request->segment(1))
            ->get();
        // dd($gateway);

        $currency_rates = DashboardSettings::get()->keyBy('key');
        $usd_rate = $currency_rates['Dollar_Rates']->value;
        $cad_rate = $currency_rates['CAD_rates']->value;
        $gbp_rate = $currency_rates['GBP_rates']->value;
        $aud_rate = $currency_rates['AUD_rates']->value;
        $sar_rate = $currency_rates['SAR_rates']->value;

        if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
            $usd_rate = $currency_rates[$txn->txn_currency_rate]->value;
        }

        $base_currency = 'PKR';
        // if ($txn->txn_currency != 'PKR') {
            $base_currency = $txn->txn_currency;
            $base_amount = $txn->txn_amount;
        // } else {
        //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
        //         $base_currency = $txn->txn_currency;
        //         $base_amount = $txn->txn_amount;
        //     } else {
        //         $base_currency = 'USD';
        //         $base_amount = $txn->txn_amount / $usd_rate;
        //     }
        // }

        if ($txn->txn_currency == 'PKR') {
            $pkr_amount = $txn->txn_amount;
        } elseif ($txn->txn_currency == 'USD') {
            $pkr_amount = $usd_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'CAD') {
            $pkr_amount = $cad_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'GBP') {
            $pkr_amount = $gbp_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'AUD') {
            $pkr_amount = $aud_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'SAR') {
            $pkr_amount = $sar_rate * $txn->txn_amount;
        }

        return view(
            'layouts.deposit_slip_transaction',
            [
                'gateway' => $gateway[0],
                'data' => $txn,
                "currency_rates" => $currency_rates,
                "base_currency" => $base_currency,
                "base_amount" => $base_amount,
                "pkr_amount" => $pkr_amount,
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deposit_slip_save($id, Request $request)
    {
        // dd($id, $request, $request->files);
        $txn = Transaction::find($id);

        $deposit_slip_exists = TransactionDepositSlip::where(['approved' => 0, 'rejected' => 0, 'txn_id' => $id])->get();
        // dd($deposit_slip_exists);
        if (count($deposit_slip_exists) > 0) {
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        // dd($request->slip_image);
        $uploaded_files = [];
        if (count($request->slip_image) > 0) {
            foreach ($request->slip_image as $key => $file) {
                $uploaded_files[] = $this->upload_linode($file, '/deposit_slips', $key);
            }
        } else {
            return 'No deposit slip uploaded';
        }
        // dd($uploaded_files);
        // if($file = $request->files('slip_image')) {
        //     $uploaded_file = $this->upload_linode($file, '/deposit_slips');
        // } else {
        //     return 'No deposit slip uploaded';
        // }

        $deposit_slip = new TransactionDepositSlip();

        $deposit_slip->txn_id = $id;
        $deposit_slip->gateway_id = $request->gateway_id;
        $deposit_slip->slip_url = $uploaded_files;
        $deposit_slip->save();

        $deposit_slip_fields = $request->except(['_token', 'gateway_id', 'slip_image']);

        // dd($deposit_slip_fields);
        foreach ($deposit_slip_fields as $key => $deposit_slip_field) {
            $field = new TransactionDepositSlipFieldsData();
            $field->deposit_slip_id = $deposit_slip->id;
            $field->field_id = $key;
            $field->field_value = $deposit_slip_field;
            $field->txn_id = $id;
            $field->save();
        }

        $user = new User();;
        $user->subject = "Deposit Slip Received Successfully";
        $user->greeting = "Dear " . $txn->txn_customer_name . ",";
        $user->message1 = "Your Deposit Slip has been received.";
        $user->message2 = "Your slip verification is under process.";
        $user->thankyou_message = "Thank you";
        $user->email = $txn->txn_customer_email;
        $user->bcc = "manual.invoice.sales@ec.com.pk";
        $user->notify(new DepositSlipReceived());

        return view('layouts.deposit_slip_thankyou_page');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jazzcash($id)
    {
        //
        $jazz_merchantid = config('gateways.jazz.merchantid');
        $jazz_password = config('gateways.jazz.password');
        $jazz_integeritysalt = config('gateways.jazz.integeritysalt');

        $txn = Transaction::find($id);
        $txn->pp_TxnDateTime = date('YmdHis', strtotime("+0 hours"));
        $txn->pp_TxnExpiryDateTime = date('YmdHis', strtotime("+9 hours"));

        $currency_rates = DashboardSettings::get()->keyBy('key'); //where('key', 'Dollar_Rates')->pluck('value')->first();
        // dd($currency_rates);
        $dollar_rate = $currency_rates['Dollar_Rates']->value;
        // $dollar_rate = DashboardSettings::where('key', 'Dollar_Rates')->pluck('value')->first();

        if ($txn->txn_currency == 'PKR') {
            $txn->txn_amount = $txn->txn_amount * 100;
        } elseif ($txn->txn_currency == 'USD') {
            $txn->txn_amount = $dollar_rate * $txn->txn_amount * 100;
        }

        $phpstring = $jazz_integeritysalt . "&" . $txn->txn_amount . "&TBANK&" . $txn->txn_customer_bill_order_id . "&" . $txn->txn_description . "&EN&" . $jazz_merchantid . "&" . $jazz_password . "&RETL&" . config('app.url') . "/jazzCallback.php&PKR&" . $txn->pp_TxnDateTime . "&" . $txn->pp_TxnExpiryDateTime . "&" . $txn->txn_reference . "&MPAY&1.1&1&2&3&4&5";
        $phphashC = hash_hmac("sha256", $phpstring,  $jazz_integeritysalt);

        $phpstring = $jazz_integeritysalt . "&" . $txn->txn_amount . "&TBANK&" . $txn->txn_customer_bill_order_id . "&" . $txn->txn_description . "&EN&" . $jazz_merchantid . "&" . $jazz_password . "&RETL&" . config('app.url') . "/jazzCallback.php&PKR&" . $txn->pp_TxnDateTime . "&" . $txn->pp_TxnExpiryDateTime . "&" . $txn->txn_reference . "&MWALLET&1.1&1&2&3&4&5";
        $phphashM = hash_hmac("sha256", $phpstring,  $jazz_integeritysalt);

        return view('layouts.jazzcash', ['transaction' => $txn, 'phpstring' => $phpstring, 'phphashC' => $phphashC, 'phphashM' => $phphashM, 'jazz_merchantid' => $jazz_merchantid, 'jazz_password' => $jazz_password]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function jazzCallback()
    {
        //
        $status = (in_array($_REQUEST['pp_ResponseCode'], array('000', '121', '200')) ? 'completed' : (in_array($_REQUEST['pp_ResponseCode'], array('124', '210', '157')) ? 'pending' : 'rejected')); // Response handling is properly mapped (000,121,200 Success 124,210,157 pending other fail)

        $gateway = Gateway::where('ec_pay_gateway_url', 'jazzcash')->first();

        $txn = Transaction::where('txn_reference', $_REQUEST['pp_TxnRefNo'])
            ->update(
                [
                    'txn_response_code' => $_REQUEST['pp_ResponseCode'],
                    'txn_ec_gateway_id' => $gateway->id, //jazzcash
                    'txn_response_ref' => $_REQUEST['pp_RetreivalReferenceNo'],
                    'txn_status' => $status,
                    'txn_response' => json_encode($_REQUEST)
                ]
            );

        $txn = Transaction::where('txn_reference', $_REQUEST['pp_TxnRefNo'])->get();
        // dd($txn);
        return redirect()->away($txn[0]['txn_platform_return_url'] . '?code=' . $_REQUEST['pp_ResponseCode'] . '&status=' . $status . '&amount=' . $_REQUEST['pp_Amount'] . '&message=' . $_REQUEST['pp_ResponseMessage'] . '&orderid=' . $_REQUEST['pp_BillReference'] . '&tnxid=' . $_REQUEST['pp_TxnRefNo']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_voucher($id)
    {
        $voucher = extract_txn_reference($id);
        $voucher = substr($voucher, 5);
        // dd($voucher);
        $txn = Transaction::where('txn_reference', $voucher)->first();

        if (strtotime($txn->txn_expiry_datetime) < strtotime("now")) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn->txn_expiry_datetime), 'time_now' => strtotime("now")]);
        }

        if ($txn && $txn->txn_status == "completed") { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        return redirect('/kuickpay/' . $txn->id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_create_token($id)
    {
        $txn = Transaction::find($id);

        if (strtotime($txn->txn_expiry_datetime) < strtotime("now")) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn->txn_expiry_datetime), 'time_now' => strtotime("now")]);
        }

        if ($txn && $txn->txn_status == "completed") { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        $currency_rates = DashboardSettings::get()->keyBy('key');
        $usd_rate = $currency_rates['Dollar_Rates']->value;
        $cad_rate = $currency_rates['CAD_rates']->value;
        $gbp_rate = $currency_rates['GBP_rates']->value;
        $aud_rate = $currency_rates['AUD_rates']->value;
        $sar_rate = $currency_rates['SAR_rates']->value;

        if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
            $usd_rate = $currency_rates[$txn->txn_currency_rate]->value;
        }

        $base_currency = 'PKR';
        // if ($txn->txn_currency != 'PKR') {
            $base_currency = $txn->txn_currency;
            $base_amount = $txn->txn_amount;
        // } else {
        //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
        //         $base_currency = $txn->txn_currency;
        //         $base_amount = $txn->txn_amount;
        //     } else {
        //         $base_currency = 'USD';
        //         $base_amount = $txn->txn_amount / $usd_rate;
        //     }
        // }

        if ($txn->txn_currency == 'PKR') {
            $pkr_amount = $txn->txn_amount;
        } elseif ($txn->txn_currency == 'USD') {
            $pkr_amount = $usd_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'CAD') {
            $pkr_amount = $cad_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'GBP') {
            $pkr_amount = $gbp_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'AUD') {
            $pkr_amount = $aud_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'SAR') {
            $pkr_amount = $sar_rate * $txn->txn_amount;
        }

        $txn->txn_response_ref = generate_txn_reference($txn->txn_reference);
        $txn->txn_expiry_datetime = (isset($txn->txn_expiry_datetime) ? $txn->txn_expiry_datetime : Date('Y-m-d H:i:s', strtotime($txn->txn_datetime . ' + 10 days')));
        $txn->save();
        $txn->txn_reference = $txn->txn_response_ref;

        return view('layouts.kuickpay', [
            'transaction' => $txn,
            'currency_rates' => $currency_rates,
            "base_currency" => $base_currency,
            "base_amount" => $base_amount,
            "pkr_amount" => $pkr_amount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_card_success($id)
    {
        dd(request()->all());

        $session = request()->all();
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'kuickpaycards';
        $ipn_logs->ipn_response = json_encode($session);
        $ipn_logs->save();

        // $txn = Transaction::where('txn_customer_bill_order_id', request()->OrderId)->first();
        $txn = Transaction::find($id);

        if (isset($txn->txn_ec_gateway_id) && $txn->txn_ec_gateway_id > 0) {
            $gateway = Gateway::find($txn->txn_ec_gateway_id);
        }


        if ($session["ResponseCode"] == '00') {
            $txn->txn_response_code = 200;
            $txn->txn_ec_gateway_id = $gateway->id;
            $txn->txn_status = 'paid';
            $txn->txn_response = json_encode($session);
            $txn->save();
        }

        $response = notify_platform_return_url($txn);

        return view('account.manual_invoices.manual_payment_thankyou');
        return response()->json([], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_card_failure($id)
    {
        dd(request()->all());
        return view('account.manual_invoices.manual_payment_thankyou');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_card_IPN($id)
    {
        dd(request()->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kuickpay_create_card($id)
    {
        $txn = Transaction::find($id);

        if (strtotime($txn->txn_expiry_datetime) < strtotime("now")) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn->txn_expiry_datetime), 'time_now' => strtotime("now")]);
        }

        if ($txn && $txn->txn_status == "completed") { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        $currency_rates = DashboardSettings::get()->keyBy('key');
        $usd_rate = $currency_rates['Dollar_Rates']->value;
        $cad_rate = $currency_rates['CAD_rates']->value;
        $gbp_rate = $currency_rates['GBP_rates']->value;
        $aud_rate = $currency_rates['AUD_rates']->value;
        $sar_rate = $currency_rates['SAR_rates']->value;

        if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
            $usd_rate = $currency_rates[$txn->txn_currency_rate]->value;
        }

        $base_currency = 'PKR';
        // if ($txn->txn_currency != 'PKR') {
            $base_currency = $txn->txn_currency;
            $base_amount = $txn->txn_amount;
        // } else {
        //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
        //         $base_currency = $txn->txn_currency;
        //         $base_amount = $txn->txn_amount;
        //     } else {
        //         $base_currency = 'USD';
        //         $base_amount = $txn->txn_amount / $usd_rate;
        //     }
        // }

        if ($txn->txn_currency == 'PKR') {
            $pkr_amount = $txn->txn_amount;
        } elseif ($txn->txn_currency == 'USD') {
            $pkr_amount = $usd_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'CAD') {
            $pkr_amount = $cad_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'GBP') {
            $pkr_amount = $gbp_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'AUD') {
            $pkr_amount = $aud_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'SAR') {
            $pkr_amount = $sar_rate * $txn->txn_amount;
        }

        $kuickpay_token = get_kuickpay_card_api_token();
        $kuickpay_token = json_decode($kuickpay_token);
        $kuickpay_signature = md5('01234' . $txn->txn_customer_bill_order_id . $pkr_amount . 'xWX+A8qbYkLgHf3e/pu6PZiycOGc0C/YXOr3XislvxI=');

        return view('layouts.kuickpaycard', [
            'transaction' => $txn,
            'currency_rates' => $currency_rates,
            "base_currency" => $base_currency,
            "base_amount" => $base_amount,
            "pkr_amount" => $pkr_amount,
            "kuickpay_token" => $kuickpay_token->auth_token,
            "kuickpay_signature" => $kuickpay_signature,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function easypay($id)
    {
        //
        $easypay_storeId = config('gateways.easypay.STORE_ID');
        $easypay_hash_key = config('gateways.easypay.HASH_KEY');

        $txn = Transaction::find($id);
        $txn->pp_TxnDateTime = date('YmdHis', strtotime("+0 hours"));
        $txn->pp_TxnExpiryDateTime = date('YmdHis', strtotime("+9 hours"));

        $currency_rates = DashboardSettings::get()->keyBy('key'); //where('key', 'Dollar_Rates')->pluck('value')->first();
        // dd($currency_rates);
        $dollar_rate = $currency_rates['Dollar_Rates']->value;
        // $dollar_rate = DashboardSettings::where('key', 'Dollar_Rates')->pluck('value')->first();

        if ($txn->txn_currency == 'PKR') {
            $txn->txn_amount = sprintf("%0.1f", $txn->txn_amount);
        } elseif ($txn->txn_currency == 'USD') {
            $txn->txn_amount = sprintf("%0.1f", ($dollar_rate * $txn->txn_amount));
        }

        $sorted_string = "amount=" . $txn->txn_amount . "&";
        $sorted_string .= "autoRedirect=" . "1&";
        $sorted_string .= "emailAddr=" . $txn->txn_customer_email . "&";
        // $sorted_string .= "expiryDate=" . $txn->expiryDate . "&";
        $sorted_string .= "mobileNum=" . $txn->txn_customer_mobile . "&";
        $sorted_string .= "orderRefNum=" . $txn->txn_reference . "&";
        // $sorted_string .= "paymentMethod=MA_PAYMENT_METHOD&";
        $sorted_string .= "postBackURL=" . config('app.url') . "/easypayCallback&";
        $sorted_string .= "storeId=" . $easypay_storeId;

        $cipher = "aes-128-ecb";
        $crypttext = openssl_encrypt($sorted_string, $cipher, $easypay_hash_key, OPENSSL_RAW_DATA);
        $hashRequest = base64_encode($crypttext);

        // $ivlen = openssl_cipher_iv_length($cipher="AES-128-ECB");
        // $iv = openssl_random_pseudo_bytes($ivlen);
        // $crypttext = openssl_encrypt($sorted_string, $cipher, $easypay_hash_key, OPENSSL_RAW_DATA, $iv);
        // $hashRequest = base64_encode($crypttext);

        return view('layouts.easypay', ['transaction' => $txn, 'easypay_storeId' => $easypay_storeId, 'merchantHashedReq' => $hashRequest]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function easypayCallback(Request $request)
    {
        return view('layouts.easypayconfirm');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function easypayIPN(Request $request)
    {
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'easypay';
        $ipn_logs->ipn_response = json_encode($request->all());
        $ipn_logs->save();

        // $txn = Transaction::where('txn_reference', $_REQUEST['pp_TxnRefNo'])
        //     ->update(
        //         [
        //             'txn_response_code' => $_REQUEST['pp_ResponseCode'],
        //             'txn_ec_gateway_id' => 2, //easypay
        //             'txn_response_ref' => $_REQUEST['pp_RetreivalReferenceNo'],
        //             'txn_status' => $status,
        //             'txn_response' => json_encode($_REQUEST)
        //         ]
        //     );

        if (isset($_GET["url"])) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $_GET["url"]);
            $output = curl_exec($curl);

            if ($output != null) {
                // $orderRefNumber = substr($_GET['url'], strrpos($_GET['url'], '/') + 1);
                // $query = "UPDATE " . $table_name . " SET ipn_attr='" . $output . "' WHERE easypay_order_id='" . $orderRefNumber . "'";
                $ipn_logs->update(
                    [
                        'ipn_response' => json_encode($output)
                    ]
                );
            }
            curl_close($curl);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paypal_create_checkout($id)
    {
        $txn = Transaction::find($id);

        $paypal_configuration = config('gateways.paypal');
        $api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $api_context->setConfig($paypal_configuration['settings']);


        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $currency_rates = DashboardSettings::get()->keyBy('key'); //where('key', 'Dollar_Rates')->pluck('value')->first();
        // dd($currency_rates);
        $dollar_rate = $currency_rates['Dollar_Rates']->value;
        // $dollar_rate = DashboardSettings::where('key', 'Dollar_Rates')->pluck('value')->first();

        if ($txn->txn_currency == 'USD') {
            $txn_amount = $txn->txn_amount;
        } elseif ($txn->txn_currency == 'PKR') {
            $txn_amount = ($txn->txn_amount / $dollar_rate);
        }

        $item_1 = new Item();

        $item_1->setName($txn->txn_description)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($txn_amount);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($txn_amount);

        $transaction = new paypaltransaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setInvoiceNumber($txn->txn_reference)
            ->setDescription($txn->txn_description);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(config('app.url') . "/paypalCallback/" . $id)
            ->setCancelUrl(config('app.url') . "/paypalCallback/" . $id);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('paywithpaypal');
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paywithpaypal');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            return redirect()->away($redirect_url);
        }

        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paypalCallback($id, Request $request)
    {
        // dd($request);

        $txn = Transaction::find($id);
        // dd($txn);
        return redirect()->away($txn->txn_platform_return_url);
        // return redirect()->away($txn[0]['txn_platform_return_url'] . '?code=' . $_REQUEST['pp_ResponseCode'] . '&status=' . $status . '&amount=' . $_REQUEST['pp_Amount'] . '&message=' . $_REQUEST['pp_ResponseMessage'] . '&orderid=' . $_REQUEST['pp_BillReference'] . '&tnxid=' . $_REQUEST['pp_TxnRefNo']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paypalIPN(Request $request)
    {
        $response = $request->all();
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'paypal';
        $ipn_logs->ipn_response = json_encode($response);
        $ipn_logs->save();

        if ($response["resource"]["payer"]["status"] == 'VERIFIED') {
            // $txn = Transaction::where('txn_reference', $result->resource->transactions[0]->invoice_number)->get();
            $gateway = Gateway::where('ec_pay_gateway_url', 'paypal')->first();

            $txn = Transaction::where('txn_reference', $response["resource"]["transactions"][0]["invoice_number"])
                ->update(
                    [
                        'txn_response_code' => 200,
                        'txn_ec_gateway_id' => $gateway->id, //paypal
                        'txn_response_ref' => $response["resource"]["id"],
                        'txn_status' => 'completed',
                        'txn_response' => json_encode($response)
                    ]
                );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe_create_checkout($id)
    {
        $segment_gateway = request()->segment(1); //returns 'stripe', 'stripep', 'stripem', 'stripeu'
        // dd($segment_gateway);
        $gateway = Gateway::where('ec_pay_gateway_url', $segment_gateway)->first();

        $txn = Transaction::find($id);
        //
        if (strtotime($txn->txn_expiry_datetime) < strtotime("now")) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn->txn_expiry_datetime), 'time_now' => strtotime("now")]);
        }

        if ($txn && $txn->txn_status == "completed") { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => "Already Paid", 'time_now' => strtotime("now")]);
        }

        if ($segment_gateway == 'stripem' || $segment_gateway == 'stripemp') {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_MY'));
        } elseif ($segment_gateway == 'stripeu' || $segment_gateway == 'stripeup') {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_US'));
        } else {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        }

        // $currency_rates = DashboardSettings::get()->keyBy('key'); //where('key', 'Dollar_Rates')->pluck('value')->first();
        // dd($currency_rates);
        // $dollar_rate = $currency_rates['Dollar_Rates']->value;
        // $dollar_rate = DashboardSettings::where('key', 'Dollar_Rates')->pluck('value')->first();

        // if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
        //     $dollar_rate = $currency_rates[$txn->txn_currency_rate]->value;
        // }

        // if ($txn->txn_currency == 'PKR') {
        //     // Test Stripe for PKR
        //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
        //         $txn_amount = $txn->txn_amount;
        //         $txn_currency = $txn->txn_currency;
        //     }else{
        //         $txn_amount = ($txn->txn_amount / $dollar_rate);
        //         $txn_currency = 'USD';
        //     }
        // } else {
            $txn_amount = $txn->txn_amount;
            $txn_currency = $txn->txn_currency;
        // }

        // Calculate fee if exists
        $txn_gateway_fee = 0;
        if ($gateway->ec_pay_gateway_fee_percent > 0) {
            $txn_gateway_fee = $txn->txn_amount * $gateway->ec_pay_gateway_fee_percent / 100;
            $txn_amount = $txn_amount + $txn_gateway_fee;
        }

        $txn_amount = $txn_amount * 100; // fix after adding gateway fee if applicable

        // dd($txn_amount, $gateway->ec_pay_gateway_fee_percent );

        $session = \Stripe\Checkout\Session::create([
            'customer_email' => $txn->txn_customer_email,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'product_data' => [
                        'name' => $txn->txn_description,
                    ],
                    'currency' => $txn_currency,
                    'unit_amount' => round($txn_amount, 0),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'client_reference_id' => $txn->txn_reference,
            'success_url' => config('app.url') . "/stripeCallback/" . $id,
            'cancel_url' => config('app.url') . "/stripeCancelUrl/" . $id,
        ]);

        // dd($session, $gateway);
        $txn->txn_ec_gateway_id = $gateway->id;
        $txn->txn_gateway_fee = $txn_gateway_fee;
        $txn->txn_response_ref = $session->payment_intent;
        $txn->save();

        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'stripe';
        $ipn_logs->ipn_response = json_encode($session);
        $ipn_logs->save();

        return redirect()->away($session->url, 303);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripeCancelUrl($id)
    {
        $txn = Transaction::find($id);

        if (strtotime($txn->txn_expiry_datetime) < strtotime("now")) { // date("Y-m-d H:i",strtotime("2022-03-02 14:37"))
            return view('layouts.transaction_expired', ['expiry_time' => strtotime($txn->txn_expiry_datetime), 'time_now' => strtotime("now")]);
        }

        $currency_rates = DashboardSettings::get()->keyBy('key');
        $usd_rate = $currency_rates['Dollar_Rates']->value;
        $cad_rate = $currency_rates['CAD_rates']->value;
        $gbp_rate = $currency_rates['GBP_rates']->value;
        $aud_rate = $currency_rates['AUD_rates']->value;
        $sar_rate = $currency_rates['SAR_rates']->value;

        if ($txn->txn_currency_rate && $txn->txn_currency == 'USD') {
            $usd_rate = $currency_rates[$txn->txn_currency_rate]->value;
        }

        $base_currency = 'PKR';
        // if ($txn->txn_currency != 'PKR') {
            $base_currency = $txn->txn_currency;
            $base_amount = $txn->txn_amount;
        // } else {
        //     if($txn->txn_customer_email == 'bilalec999@gmail.com'){
        //         $base_currency = $txn->txn_currency;
        //         $base_amount = $txn->txn_amount;
        //     } else {
        //         $base_currency = 'USD';
        //         $base_amount = $txn->txn_amount / $usd_rate;
        //     }
        // }

        if ($txn->txn_currency == 'PKR') {
            $pkr_amount = $txn->txn_amount;
        } elseif ($txn->txn_currency == 'USD') {
            $pkr_amount = $usd_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'CAD') {
            $pkr_amount = $cad_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'GBP') {
            $pkr_amount = $gbp_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'AUD') {
            $pkr_amount = $aud_rate * $txn->txn_amount;
        } elseif ($txn->txn_currency == 'SAR') {
            $pkr_amount = $sar_rate * $txn->txn_amount;
        }

        $gateways = Gateway::where(['ec_pay_gateway_enabled' => 1]);
        // dd($txn->txn_gateway_options);
        if (isset($txn->txn_gateway_options) && $txn->txn_gateway_options) {
            $txn_gateway_options = json_decode($txn->txn_gateway_options);
            // dd($txn_gateway_options);
            $gateways = $gateways->whereIn('ec_pay_gateway_url', $txn_gateway_options);
        }

        $gateways = $gateways->get()->sortBy('ec_pay_gateway_sort');

        return view('layouts.gateways2', [
            "data" => $txn,
            "gateways" => $gateways,
            "currency_rates" => $currency_rates,
            "base_currency" => $base_currency,
            "base_amount" => $base_amount,
            "pkr_amount" => $pkr_amount,
            'cancelmsg' => 'The payment process was incomplete, kindly choose the payment method below to proceed'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripeCallback($id, Request $request)
    {
        // dd($request);
        sleep(5);
        $txn = Transaction::find($id);
        // dd($txn);
        $fields = [
            'status' => (isset($txn->txn_status) ? $txn->txn_status : 'Pending confirmation from Stripe'),
            'orderid' => $txn->txn_customer_bill_order_id,
            'amount' => $txn->txn_amount
        ];

        $key = env('EC_INTRA_COMM_KEY');
        $cipher = env('EC_INTRA_COMM_CIPHER');
        $iv = env('EC_INTRA_COMM_IV');
        $iv = hex2bin($iv);

        $plaintext = http_build_query($fields);
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
            $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
        }
        
        return redirect()->away(
            $txn->txn_platform_return_url .
                '?hash=' . $ciphertext .
                '&status=' . (isset($txn->txn_status) ? $txn->txn_status : 'Pending confirmation from Stripe') .
                '&orderid=' . $txn->txn_customer_bill_order_id .
                '&amount=' . $txn->txn_amount
        );
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

    //     $key = env('EC_INTRA_COMM_KEY');
    //     $cipher = env('EC_INTRA_COMM_CIPHER');
    //     $iv = env('EC_INTRA_COMM_IV');
    //     $iv = hex2bin($iv);

    //     $plaintext = http_build_query($fields);
    //     if (in_array($cipher, openssl_get_cipher_methods())) {
    //         $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
    //         $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
    //     }
    //     $postvars .= "hash=" . $ciphertext;

    //     curl_setopt($ch, CURLOPT_URL, $transaction->txn_platform_return_url. "?" . $postvars);
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

    public function generate_send_payment_receipt($txn)
    {
        $data = $txn->toArray();
        // $uploaded_pdf = $this->upload_linode($pdf, '/transaction_receipts', '_' . $txn->id);
        $uploaded_pdf = 'transaction_receipts_'.time(). '_' . $txn->id. ".pdf";
        $pdf = PDF::loadView('receipts.transaction_receipt', $data)->save(public_path('receipts') . '/' . $uploaded_pdf);

        $user = new User();
        $user->subject = "Payment Receipt - " . $txn->txn_customer_bill_order_id;
        $user->greeting = "Dear " . $txn->txn_customer_name . ",";
        $user->message1 = "Please find your recent payment receipt made to Extreme Commmerce.";
        $user->message2 = "Invoice #: " .$txn->txn_customer_bill_order_id . 
        "<br> Payment Gateway: ". $txn->gateway->ec_pay_gateway_name .
        "<br> Reference#: ". $txn->txn_response_ref .
        "<br> Details: " . $txn->txn_description .
        "<br> Created On: " . date('dS M, Y H:s:i', strtotime($txn->created_at)) .
        "<br> Paid On: ". date('dS M, Y H:s:i', strtotime($txn->txn_datetime));
        $user->thankyou_message = "Thank you";
        $user->email = $txn->txn_customer_email; // 'shoaib.iqbal@ec.com.pk'; //
        $user->bcc = 'sales@ec.com.pk';
        $user->attach   = public_path('receipts') . '/' . $uploaded_pdf;
        if(isset(request()->debug)){
            dd($pdf, $user, $uploaded_pdf, $user->notify(new PaymentReceipt()));
        }
        return $user->notify(new PaymentReceipt());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stripeIPN(Request $request)
    {
        //
        $session = $request->all();
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'stripe';
        $ipn_logs->ipn_response = json_encode($session);
        $ipn_logs->save();

        if ($session["type"] == 'checkout.session.expired') {
            return response()->json([], 200);
        }

        // Log::info(print_r($session,true));
        
        
        if ($session["type"] == 'checkout.session.completed') {
            // Change in stripe api which is not sending payment_intent on checkout
            $txn = Transaction::where('txn_reference', $session["data"]["object"]["client_reference_id"])->first();
            
            if (isset($txn->txn_ec_gateway_id) && $txn->txn_ec_gateway_id > 0) {
                $gateway = Gateway::find($txn->txn_ec_gateway_id);
            } else {
                $gateway_slug = ($txn->gateway_fee > 0 ? 'stripep' : 'stripe');
                $gateway = Gateway::where('ec_pay_gateway_url', $gateway_slug)->first();
            }
            $txn->txn_response_code = 200;
            $txn->txn_ec_gateway_id = $gateway->id;
            $txn->txn_status = ($session["data"]["object"]["payment_status"] == 'paid' ? 'completed' : $session["data"]["object"]["payment_status"]);
            $txn->txn_response = json_encode($session);
            $txn->txn_response_ref = $session["data"]["object"]["payment_intent"];
            $txn->save();
            $txn->ec_pay_gateway_name = $txn->gateway->ec_pay_gateway_name;
            $sent = $this->generate_send_payment_receipt($txn);
            // dd($sent);
        }

        if ($session["type"] == 'charge.refunded') {
            $txn = Transaction::where('txn_response_ref', $session["data"]["object"]["payment_intent"])->first();

            $txn->txn_status = 'refunded';
            $txn->save();
        }

        if ($session["type"] == 'charge.dispute.closed') {
            if ($session["data"]["object"]["status"] == 'lost') {
                $txn = Transaction::where('txn_response_ref', $session["data"]["object"]["payment_intent"])->first();
                $txn->txn_status = 'refunded';
                $txn->save();
            }
        }

        // $response = $this->notify_platform_return_url($txn);
        $response = notify_platform_return_url($txn);

        return response()->json([], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
