<?php

namespace App\Models;

use App\Gateway;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $fillable = [
        'txn_amount',
        'txn_gateway_fee',
        'txn_currency',
        'txn_customer_id',
        'txn_customer_name',
        'txn_customer_email',
        'txn_customer_mobile',
        'txn_payment_type',
        'txn_customer_bill_order_id',
        'txn_reference',
        'txn_gateway_options',
        'txn_ec_platform_id',
        'txn_ec_gateway_id',
        'txn_datetime',
        'txn_expiry_datetime',
        'txn_description',
        'txn_status',
        'txn_request',
        'txn_response',
        'txn_response_code',
        'txn_response_ref',
        'txn_platform_return_url',
        'customer_ip'
    ];

    
    public function gateway(){
        return $this->hasOne(Gateway::class, 'id', 'txn_ec_gateway_id');
    }
}
