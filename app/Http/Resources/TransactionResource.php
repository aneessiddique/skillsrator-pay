<?php

namespace App\Http\Resources;

use App\Gateway;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this[0])){
            $txn = $this[0] ;
        } else { 
            return parent::toArray($request);
        }

        $gateway_response_message = '';
        // if(isset($txn->txn_response->pp_ResponseMessage)){
        //     $gateway_response_message = $txn->txn_response->pp_ResponseMessage;
        // }
        $gateway_response_message = json_decode($txn->txn_response);
        if(isset($gateway_response_message->pp_ResponseMessage)){
            $gateway_response_message = $gateway_response_message->pp_ResponseMessage;
        }

        return [
            "amount" => $txn->txn_amount,
            "currency" => $txn->txn_currency,
            "customer_id" => $txn->txn_customer_id,
            "customer_name" => $txn->txn_customer_name,
            "customer_email" => $txn->txn_customer_email,
            "customer_mobile" => $txn->txn_customer_mobile,
            "payment_type" => $txn->txn_payment_type,
            "customer_bill_order_id" => $txn->txn_customer_bill_order_id,
            // "reference" => $txn->txn_reference,
            // "ec_platform_id" => $txn->txn_ec_platform_id,
            "payment_gateway" => ($txn->txn_ec_gateway_id > 0) ? Gateway::find($txn->txn_ec_gateway_id)->ec_pay_gateway_name : 0,
            // "datetime" => $txn->txn_datetime,
            // "expiry_datetime" => $txn->txn_expiry_datetime,
            "description" => $txn->txn_description,
            "status" => $txn->txn_status,
            // "request" => $txn->txn_request,
            "response" => $gateway_response_message,
            // "response_code" => $txn->txn_response_code,
            // "response_ref" => $txn->txn_response_ref,
            "return_url" => $txn->txn_platform_return_url,
            // "omer_ip" => $txn->customer_ip,
            // "ted_at" => $txn->created_at,
            // "ted_at" => $txn->updated_at
        ];

        
        // return parent::toArray($request);
    }
}
