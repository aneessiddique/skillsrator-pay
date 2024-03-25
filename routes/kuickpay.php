<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\kuickpayController;
use App\Transaction;

/*
|--------------------------------------------------------------------------
| KUICKPAY Routes
|--------------------------------------------------------------------------
|
| Here is where you can register KUICKPAY routes for your application.
|
*/

// Route::any('kuickpay', [kuickpayController::class,'runserver']);

// Route::any('kuickpay', function () {

//     $server = new \nusoap_server();
//     $server->configureWSDL("kuickpay", 'urn://kuickpay/res', "http://localhost:8000/kuickpay/");

//     $server->register(
//         'echoa',
//         array('input' => 'xsd:string'),
//         array('output' => 'xsd:string'),
//         'urn://kuickpay/res',
//         'http://localhost:8000/kuickpay/echoa'
//     );

//     function echoa($input)
//     {
//         return $input;
//     }

//     $rawPostData = file_get_contents("php://input");
//     return \Response::make($server->service($rawPostData), 200, array('Content-Type' => 'text/xml; charset=ISO-8859-1'));
// });


Route::any('kuickpay/BillInquiry', function () {

    function BillInquiry($UserName, $Password, $ConsumerNumber, $bankMnemonic, $reserved)
    {
        try {
            $KuickpayUserName = config('gateways.kuickpay.UserName');
            $KuickpayPass = config('gateways.kuickpay.Pass');

            if ($KuickpayUserName == $UserName && $KuickpayPass == $Password) {
                $transaction = Transaction::where('txn_reference', $ConsumerNumber)->get();

                $transaction = isset($transaction[0]) ? $transaction[0] : false;
                // return $transaction[0];
                if ($transaction) {
                    $status = $transaction->txn_status;

                    if ($transaction->txn_datetime <= strtotime($transaction->txn_datetime . '+ 3 days')) {
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

                    $Consumer_Detail = str_pad($transaction->txn_customer_name, 30, ".");
                    $Bill_Status = $s;
                    $Due_Date = date("dm", (strtotime($transaction->txn_datetime)));
                    $Amount_Within_DueDate = "+" . str_pad($transaction->txn_amount . '00', 13, "0", STR_PAD_LEFT);
                    $Amount_After_DueDate = $Amount_Within_DueDate;
                    $Billing_Month = date("dm", (strtotime($transaction->txn_datetime)));

                    $data = $Consumer_Detail . '' . $Bill_Status . '' . $Due_Date . '' . $Amount_Within_DueDate . '' . $Amount_After_DueDate . '' . $Billing_Month;
                    $res = $transaction->txn_customer_email . ',' . $transaction->txn_customer_mobile;

                    return "00" . $data . "" . $res . ""; // ‘00’ in case of valid inquiry number that exists in the system/database  and status is active

                } else {
                    $v = str_pad("01", 299, " ", STR_PAD_RIGHT); // ‘01’ in case of invalid inquiry number that does not exists
                    return $v;
                }
            } else {
                $v = str_pad("04", 299, " ", STR_PAD_RIGHT); // ‘04’ Invalid Data
                return $v;
            }
        } catch (Exception $e) {
            $v = str_pad("05", 299, " ", STR_PAD_RIGHT); // ‘05’ Processing Failed
            return $v;
        }
    }
    $array_billinquiry = array('UserName' => 'xsd:string', 'Password' => 'xsd:string', 'ConsumerNumber' => 'xsd:string', 'bankMnemonic' => 'xsd:string', 'reserved' => 'xsd:string');
    $server = new soap_server();
    $server->configureWSDL("kuickpay", "https://sbpay.ec.com.pk/kuickpay/");
    $server->wsdl->schemaTargetNamespace = "https://sbpay.ec.com.pk/kuickpay/";
    $server->register("BillInquiry", $array_billinquiry, array("BillInquiryResult" => 'xsd:string'), 'https://sbpay.ec.com.pk/kuickpay/', 'https://sbpay.ec.com.pk/kuickpay/BillInquiry');
    
    if (!isset($HTTP_RAW_POST_DATA)) $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    $server->service($HTTP_RAW_POST_DATA);
});


Route::any('kuickpay', function () {

    function echoa($input)
    {
        return $input;
    }

    function BillInquiry($UserName, $Password, $ConsumerNumber, $bankMnemonic, $reserved)
    {
        try {
            $KuickpayUserName = config('gateways.kuickpay.UserName');
            $KuickpayPass = config('gateways.kuickpay.Pass');

            if ($KuickpayUserName == $UserName && $KuickpayPass == $Password) {
                $transaction = Transaction::where('txn_reference', $ConsumerNumber)->get();

                $transaction = isset($transaction[0]) ? $transaction[0] : false;
                // return $transaction[0];
                if ($transaction) {
                    $status = $transaction->txn_status;

                    if ($transaction->txn_datetime <= strtotime($transaction->txn_datetime . '+ 3 days')) {
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

                    $Consumer_Detail = str_pad($transaction->txn_customer_name, 30, ".");
                    $Bill_Status = $s;
                    $Due_Date = date("dm", (strtotime($transaction->txn_datetime)));
                    $Amount_Within_DueDate = "+" . str_pad($transaction->txn_amount . '00', 13, "0", STR_PAD_LEFT);
                    $Amount_After_DueDate = $Amount_Within_DueDate;
                    $Billing_Month = date("dm", (strtotime($transaction->txn_datetime)));

                    $data = $Consumer_Detail . '' . $Bill_Status . '' . $Due_Date . '' . $Amount_Within_DueDate . '' . $Amount_After_DueDate . '' . $Billing_Month;
                    $res = $transaction->txn_customer_email . ',' . $transaction->txn_customer_mobile;

                    return "00" . $data . "" . $res . ""; // ‘00’ in case of valid inquiry number that exists in the system/database  and status is active

                } else {
                    $v = str_pad("01", 299, " ", STR_PAD_RIGHT); // ‘01’ in case of invalid inquiry number that does not exists
                    return $v;
                }
            } else {
                $v = str_pad("04", 299, " ", STR_PAD_RIGHT); // ‘04’ Invalid Data
                return $v;
            }
        } catch (Exception $e) {
            $v = str_pad("05", 299, " ", STR_PAD_RIGHT); // ‘05’ Processing Failed
            return $v;
        }
    }

    function BillPayment($UserName, $Password, $ConsumerNumber, $authID, $amount, $tranDate, $tranTime, $bankMnemonic, $reserved)
    {
        try {
            $KuickpayUserName = config('gateways.kuickpay.UserName');
            $KuickpayPass = config('gateways.kuickpay.Pass');

            if ($KuickpayUserName == $UserName && $KuickpayPass == $Password) {

                $transaction = Transaction::where('txn_reference', $ConsumerNumber)->get();
                $transaction = isset($transaction[0]) ? $transaction[0] : false;

                if ($transaction && $transaction->txn_status == "draft" && $transaction->txn_datetime <= strtotime($transaction->txn_datetime . '+ 3 days')) {
                    // 'txn_response_code' => $_REQUEST['pp_ResponseCode'],
                    $transaction->txn_response_ref = $authID;
                    $transaction->txn_status = 'completed';
                    $transaction->txn_response = json_encode([
                        'authID' => $authID,
                        'amount' => $amount,
                        'tranDate' => $tranDate,
                        'tranTime' => $tranTime,
                        'bankMnemonic' => $bankMnemonic,
                        'reserved' => $reserved,
                    ]);
                    $transaction->save();

                    $tranid = str_pad($ConsumerNumber, 20, " ", STR_PAD_LEFT);
                    $v = str_pad($tranid, 220, " ");
                    return "00" . $tranid;
                } else {
                    if (!$transaction) {
                        return "01"; // ‘01’ in case of invalid Voucher number that does not exists
                    }
                    if ($transaction) {
                        return "02"; // ‘02’ in case of valid Voucher number that is currently blocked or dormant/inactive (i-e transactions are not allowed)
                    }

                    return "04"; // ‘04’ Invalid Data
                }
            }
        } catch (Exception $e) {
            // echo $e;
            $v = str_pad("05", 299, " ", STR_PAD_RIGHT); // ‘05’ Processing Failed
            return $v;
        }
    }

    $array_billinquiry = array('UserName' => 'xsd:string', 'Password' => 'xsd:string', 'ConsumerNumber' => 'xsd:string', 'bankMnemonic' => 'xsd:string', 'reserved' => 'xsd:string');
    $array_billpayment = array('UserName' => 'xsd:string', 'Password' => 'xsd:string', 'ConsumerNumber' => 'xsd:string', 'authID' => 'xsd:string', 'amount' => 'xsd:string', 'tranDate' => 'xsd:string', 'tranTime' => 'xsd:string', 'bankMnemonic' => 'xsd:string', 'reserved' => 'xsd:string');
    $server = new soap_server();
    $server->configureWSDL("kuickpay", "https://sbpay.ec.com.pk/kuickpay/", config('app.url')."/kuickpay/");
    $server->wsdl->schemaTargetNamespace = "https://sbpay.ec.com.pk/kuickpay/";
    $server->register("echoa", array(), array("echo" => 'xsd:string'));
    $server->register("BillInquiry", array(), array("BillInquiryResult" => 'xsd:string'));
    // $server->register("BillInquiry", $array_billinquiry, array("BillInquiryResult" => 'xsd:string'), 'https://sbpay.ec.com.pk/kuickpay/', 'https://sbpay.ec.com.pk/kuickpay/BillInquiry');
    $server->register("BillPayment", array(), array("BillPaymentResult" => 'xsd:string'), 'http://kuickpay.com/');

    if (!isset($HTTP_RAW_POST_DATA)) $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    $server->service($HTTP_RAW_POST_DATA);
});
