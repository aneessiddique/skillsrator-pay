<?php

namespace App\Http\Controllers;

// use App\Http\Server;
use App\DashboardSettings;
use App\Gateway;
use App\IPNLogs;
use App\Models\Transaction;
use App\Notifications\PaymentReceipt;
use App\User;
use PDF;
use Exception;
use Illuminate\Http\Request;

class kuickpayController extends Controller
{

    public function echoa(Request $request) {
        return response()->json($request->ping);
    }

    public function billInquiry(Request $request) {
        $session = $request->all();
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'kuickpay';
        $ipn_logs->ipn_response = 'billInquiry'.json_encode(explode('/',request()->getRequestUri())).json_encode($session).json_encode($_REQUEST);
        $ipn_logs->save();
        try {
            // $KuickpayUserName = config('gateways.kuickpay.UserName');
            // $KuickpayPass = config('gateways.kuickpay.Pass');

            $extract_txn_reference = extract_txn_reference($request->ConsumerNumber);
            // if ($KuickpayUserName == $request->UserName && $KuickpayPass == $request->Password) {
                // $transaction = Transaction::where(['txn_status' => 'draft', 'txn_reference' => $extract_txn_reference])->get();
                $transaction = Transaction::where(['txn_reference' => $extract_txn_reference])->get();

$ipn_logs->ipn_response = json_encode($extract_txn_reference).json_encode($transaction);
$ipn_logs->save();

                $transaction = isset($transaction[0]) ? $transaction[0] : false;
                // return $transaction[0];
                if ($transaction) {
                    $status = $transaction->txn_status;

                    $currency_rates = DashboardSettings::get()->keyBy('key');
                    $usd_rate = $currency_rates['Dollar_Rates']->value;
                    $cad_rate = $currency_rates['CAD_rates']->value;
                    $gbp_rate = $currency_rates['GBP_rates']->value;
                    $aud_rate = $currency_rates['AUD_rates']->value;
                    $sar_rate = $currency_rates['SAR_rates']->value;

                    if ($transaction->txn_currency_rate && $transaction->txn_currency == 'USD') {
                        $usd_rate = $currency_rates[$transaction->txn_currency_rate]->value;
                    }

                    // $base_currency = 'PKR';
                    // if ($transaction->txn_currency != 'PKR') {
                    //     $base_currency = $transaction->txn_currency;
                    //     $base_amount = $transaction->txn_amount;
                    // } else {
                    //     $base_currency = 'USD';
                    //     $base_amount = $transaction->txn_amount / $usd_rate;
                    // }

                    if ($transaction->txn_currency == 'PKR') {
                        $pkr_amount = $transaction->txn_amount;
                    } elseif ($transaction->txn_currency == 'USD') {
                        $pkr_amount = $usd_rate * $transaction->txn_amount;
                    } elseif ($transaction->txn_currency == 'CAD') {
                        $pkr_amount = $cad_rate * $transaction->txn_amount;
                    } elseif ($transaction->txn_currency == 'GBP') {
                        $pkr_amount = $gbp_rate * $transaction->txn_amount;
                    } elseif ($transaction->txn_currency == 'AUD') {
                        $pkr_amount = $aud_rate * $transaction->txn_amount;
                    } elseif ($transaction->txn_currency == 'SAR') {
                        $pkr_amount = $sar_rate * $transaction->txn_amount;
                    }

                    $pkr_amount = intval(ceil($pkr_amount));

                    if (strtotime("now") <= strtotime($transaction->txn_expiry_datetime)) {
                        if ($status == 'completed') {
                            $s = 'P'; //paid
                        } else if ($status == 'draft') {
                            $s = 'U'; //unpaid
                        } else {
                            $s = 'B'; //expired
                        }
                    } else {
                        $s = 'B'; //expired
                    }

if(isset($request->mode) && $request->mode == 'txn'){
dump($pkr_amount);
dump($transaction);
}
                    $txn_customer_name = strlen($transaction->txn_customer_name) > 30 ? substr($transaction->txn_customer_name,0,30) : $transaction->txn_customer_name;
                    $Consumer_Detail = str_pad($txn_customer_name, 30, ".");
                    $Bill_Status = $s;
                    $Due_Date = date("Ymd", (strtotime($transaction->txn_expiry_datetime)));
                    $Amount_Within_DueDate = "+" . str_pad($pkr_amount . '00', 13, "0", STR_PAD_LEFT);
                    $Amount_After_DueDate = $Amount_Within_DueDate;
                    $Billing_Month = date("dm", (strtotime($transaction->txn_datetime)));

                    $data = $Consumer_Detail . '' . $Bill_Status . '' . $Due_Date . '' . $Amount_Within_DueDate . '' . $Amount_After_DueDate . '' . $Billing_Month;
                    $res = $transaction->txn_customer_email . ',' . $transaction->txn_customer_mobile;

                    $res_data = [
                        'Response_Code' => '00',
                        'Consumer_Detail' => $Consumer_Detail,
                        'Bill_Status' => $Bill_Status,
                        'Due_Date' => $Due_Date,
                        'Amount_Within_DueDate' => $Amount_Within_DueDate,
                        'Amount_After_DueDate' => $Amount_After_DueDate,
                        'Billing_Month' => $Billing_Month,
                    ];
                    if($transaction->txn_status == 'completed'){
                        $res_data['Date_Paid'] = date("Ymd", (strtotime($transaction->updated_at)));
                        $res_data['Amount_Paid'] = $pkr_amount;
                        $res_data['Tran_Auth_Id'] = ($transaction->txn_response_ref ? $transaction->txn_response_ref : " ");
                    }
                    if($s == 'P' || $s == 'B'){
                        $res_data['Response_Code'] = '02';
                    }
                    return response()->json(["data" => $res_data, "string" => "00" . $data . "" . $res . ""]); // ‘00’ in case of valid inquiry number that exists in the system/database  and status is active

                } else {
                    $v = str_pad("01", 299, " ", STR_PAD_RIGHT); // ‘01’ in case of invalid inquiry number that does not exists
                    return response()->json($v);
                }
            // } else {
            //     $v = str_pad("04", 299, " ", STR_PAD_RIGHT); // ‘04’ Invalid Data
            //     return response()->json($v);
            // }
        } catch (Exception $e) {
            if(isset(request()->mode) && request()->mode == 'error'){
                return response()->json($e);
            }
            $v = str_pad("05", 299, " ", STR_PAD_RIGHT); // ‘05’ Processing Failed
            return response()->json($v);
        }
    }

    public function billPayment(Request $request)
    {
        $session = $request->all();
        $ipn_logs = new IPNLogs;
        $ipn_logs->ipn_gateway = 'kuickpay';
        $ipn_logs->ipn_response = 'billpayment'.json_encode(explode('/',request()->getRequestUri())).json_encode($session).json_encode($_REQUEST);
        $ipn_logs->save();

        try {
        //     $KuickpayUserName = config('gateways.kuickpay.UserName');
        //     $KuickpayPass = config('gateways.kuickpay.Pass');

        //     if ($KuickpayUserName == $UserName && $KuickpayPass == $Password) {

            $extract_txn_reference = extract_txn_reference($request->ConsumerNumber);

                $transaction = Transaction::where('txn_reference', $extract_txn_reference)->get();
                $transaction = isset($transaction[0]) ? $transaction[0] : false;
if($request->mode && $request->mode == 'debug1'){
    dd($extract_txn_reference, time(), strtotime($transaction->txn_expiry_datetime), $transaction);
}
                $gateway = Gateway::where('ec_pay_gateway_url', 'kuickpay')->first();

                // if ($transaction && $transaction->txn_status == "draft" && $transaction->txn_datetime <= strtotime($transaction->txn_datetime . '+ 3 days')) {
                if ($transaction && $transaction->txn_status == "draft" && time() <= strtotime($transaction->txn_expiry_datetime)) {
                    // 'txn_response_code' => $_REQUEST['pp_ResponseCode'],
                    $transaction->txn_response_ref = $request->ConsumerNumber;
                    $transaction->txn_status = 'completed';
                    $transaction->txn_ec_gateway_id = $gateway->id;
                    $transaction->txn_response = json_encode($request->all());
                    $transaction->save();

                    // $response = $this->notify_platform_return_url($transaction);
                    $response = notify_platform_return_url($transaction);
if($request->mode && $request->mode == 'debug2'){
    dd(notify_platform_return_url($transaction));
}
                    $tranid = str_pad($request->ConsumerNumber, 20, " ", STR_PAD_LEFT);
                    $v = str_pad($tranid, 220, " ");

                    $transaction->ec_pay_gateway_name = $transaction->gateway->ec_pay_gateway_name;
                    $sent = $this->generate_send_payment_receipt($transaction);

                    return response()->json(["data" => $request->ConsumerNumber, "string" => "00" . $v]);
                } else {
                    if (!$transaction) {
                        return response()->json("01"); // ‘01’ in case of invalid Voucher number that does not exists
                    }
                    if ($transaction) {
                        return response()->json("02"); // ‘02’ in case of valid Voucher number that is currently blocked or dormant/inactive (i-e transactions are not allowed)
                    }

                    return response()->json("04"); // ‘04’ Invalid Data
                }
            // }
        } catch (Exception $e) {
            if(isset($request->mode) && $request->mode == 'error'){
                return response()->json($e);
            }
            // echo $e;
            $v = str_pad("05", 299, " ", STR_PAD_RIGHT); // ‘05’ Processing Failed
            return response()->json($v);
        }
    }

    public function generate_send_payment_receipt($txn)
    {
        $data = $txn->toArray();
        // $uploaded_pdf = $this->upload_linode($pdf, '/transaction_receipts', '_' . $txn->id);
        $uploaded_pdf = 'transaction_receipts_'.time(). '_' . $txn->id. ".pdf";
        $pdf = PDF::loadView('receipts.transaction_receipt', $data)->save(public_path('receipts') . '/' . $uploaded_pdf);

        $user = new User();
        $user->subject = "Payment Receipt - " . $txn->txn_customer_bill_order_id;
        $user->greeting = "Dear " . $txn->txn_customer_name . ",";
        $user->message1 = "Please find your recent payment receipt made to Skillsrator.";
        $user->message2 = "Invoice #: " .$txn->txn_customer_bill_order_id .
        "<br> Payment Gateway: ". $txn->gateway->ec_pay_gateway_name .
        "<br> Reference#: ". $txn->txn_response_ref .
        "<br> Details: " . $txn->txn_description .
        "<br> Created On: " . date('dS M, Y H:s:i', strtotime($txn->created_at)) .
        "<br> Paid On: ". date('dS M, Y H:s:i', strtotime($txn->txn_datetime));
        $user->thankyou_message = "Thank you";
        $user->email = $txn->txn_customer_email; // 'shoaib.iqbal@ec.com.pk';
        $user->bcc = 'sales@ec.com.pk';
        $user->attach   = public_path('receipts') . '/' . $uploaded_pdf;
        if(isset(request()->debug)){
            dd($pdf, $user, $uploaded_pdf, $user->notify(new PaymentReceipt()));
        }
        return $user->notify(new PaymentReceipt());
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
}
