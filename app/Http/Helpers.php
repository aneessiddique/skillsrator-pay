<?php

use App\IPNLogs;
use Illuminate\Support\Facades\Log;

if (!function_exists('extract_txn_reference')) {
    function extract_txn_reference($ConsumerNumber)
    {
        $haystack = $ConsumerNumber;

        $extract_prefix = substr($haystack, 0, 5);
        if($extract_prefix == '01910'){
            $needle = config('gateways.kuickpay.token_ref');
            $prefix = config('gateways.kuickpay.token_prefix');
        } elseif($extract_prefix == '06880') {
            $needle = config('gateways.kuickpay2.token_ref');
            $prefix = config('gateways.kuickpay2.token_prefix');
        } else {
            return "not found";
        }

        $replace = 'ref';
        
        // $request_uri = explode('/',request()->getRequestUri());
        // // dd('extract_txn_reference', $request_uri);
        // if($request_uri[1] == 'kuickpay2' || $request_uri[2] == 'kuickpay2'){
        //     $needle = config('gateways.kuickpay2.token_ref');
        //     $prefix = config('gateways.kuickpay2.token_prefix');
        // }
        
        $pos = strpos($haystack, $needle);
        // dd($replace, $prefix, $haystack, $needle, $pos);
        if ($pos !== false) {
            $newstring = substr_replace($haystack, $replace, $pos, strlen($needle));
            $newstring = substr($newstring, strlen($prefix));
        }
        return $newstring;
    }
}

if (!function_exists('generate_txn_reference')) {
    function generate_txn_reference($ConsumerNumber)
    {
        $haystack = $ConsumerNumber;
        $needle = 'ref';
        $replace = config('gateways.kuickpay.token_ref');
        $prefix = config('gateways.kuickpay.token_prefix');
        
        $request_uri = explode('/',request()->getRequestUri());
        // dd('generate_txn_reference', $request_uri);
        
        $ipn_logs = new IPNLogs();
        $ipn_logs->ipn_gateway = 'kuickpay';
        $ipn_logs->ipn_response = json_encode($request_uri);
        $ipn_logs->save();

        if($request_uri[1] == 'kuickpay2' || $request_uri[2] == 'kuickpay2'){
            $replace = config('gateways.kuickpay2.token_ref');
            $prefix = config('gateways.kuickpay2.token_prefix');
        }

        $pos = strpos($haystack, $needle);
        if ($pos !== false) {
            $newstring = substr_replace($haystack, $replace, $pos, strlen($needle));
        }
        return $prefix . $newstring;
    }
}

if (!function_exists('notify_platform_return_url')) {

    function notify_platform_return_url($transaction)
    {

        $ch = curl_init();

        $fields = array(
            'code' => 200,
            'status' => $transaction->txn_status,
            'amount' => $transaction->txn_amount,
            // 'message' => $Message,
            'orderid' => $transaction->txn_customer_bill_order_id,
            'tnxid' => $transaction->id
        );
        $postvars = '';
        foreach ($fields as $key => $value) {
            $postvars .= $key . "=" . $value . "&";
        }

        $key = env('EC_INTRA_COMM_KEY');
        $cipher = env('EC_INTRA_COMM_CIPHER');
        $iv = env('EC_INTRA_COMM_IV');
        $iv = hex2bin($iv);

        $plaintext = http_build_query($fields);
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
            $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
        }
        $postvars = "hash=" . $ciphertext . "&" . $postvars;

        Log::error('return_url.encryption_vars', [
            'fields' => $fields,
            'hash' => $postvars,
            'ciphertext' => $ciphertext,
            'cipher' => $cipher,
            'iv' => $iv,
            'tag' => $tag,
            'txn_platform_return_url' => $transaction->txn_platform_return_url . "?" . $postvars,
        ]);

        curl_setopt($ch, CURLOPT_URL, $transaction->txn_platform_return_url . "?" . $postvars);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        // print "curl response is:" . $response;
        curl_close($ch);
        return $response;
    }
}

if (!function_exists('get_kuickpay_card_api_token')) {

    function get_kuickpay_card_api_token()
    {

        $ch = curl_init();

        $fields = array(
            'institutionID' => '01234',
            'kuickpaySecuredKey' => "xWX+A8qbYkLgHf3e/pu6PZiycOGc0C/YXOr3XislvxI=",
        );
        $postvars = '{"institutionID":"01234","kuickpaySecuredKey":"xWX+A8qbYkLgHf3e/pu6PZiycOGc0C/YXOr3XislvxI="}';
        // foreach ($fields as $key => $value) {
        //     $postvars .= $key . "=" . $value . "&";
        // }

        // $key = env('EC_INTRA_COMM_KEY');
        // $cipher = env('EC_INTRA_COMM_CIPHER');
        // $iv = env('EC_INTRA_COMM_IV');
        // $iv = hex2bin($iv);

        // $plaintext = http_build_query($fields);
        // if (in_array($cipher, openssl_get_cipher_methods())) {
        //     $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options = 0, $iv, $tag);
        //     $ciphertext = $ciphertext . bin2hex($tag); // tag variable generated from encrypt
        // }
        // $postvars = "hash=" . $ciphertext . "&" . $postvars;

        // Log::error('return_url.encryption_vars', [
        //     'fields' => $fields,
        //     'hash' => $postvars,
        //     'ciphertext' => $ciphertext,
        //     'cipher' => $cipher,
        //     'iv' => $iv,
        //     'tag' => $tag,
        // ]);

        $headers = array(
            "Content-Type: application/json",
         );
         
        curl_setopt($ch, CURLOPT_URL, "https://app2.kuickpay.com:5728/api/KPToken");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        // print "curl response is:" . $response;
        curl_close($ch);

        return $response;
    }
}

