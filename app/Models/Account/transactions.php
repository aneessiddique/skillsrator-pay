<?php

namespace App\Models\Account;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class transactions
 * @package App\Models\Account
 * @version April 1, 2022, 5:35 pm UTC
 *
 * @property integer $txn_amount
 * @property number $txn_gateway_fee
 * @property string $txn_currency
 * @property string $txn_customer_id
 * @property string $txn_customer_name
 * @property string $txn_customer_email
 * @property string $txn_customer_mobile
 * @property string $txn_payment_type
 * @property string $txn_customer_bill_order_id
 * @property string $txn_reference
 * @property string $txn_gateway_options
 * @property integer $txn_ec_platform_id
 * @property string $txn_ec_gateway_id
 * @property string|\Carbon\Carbon $txn_datetime
 * @property string|\Carbon\Carbon $txn_expiry_datetime
 * @property string $txn_description
 * @property string $txn_status
 * @property string $txn_request
 * @property string $txn_response
 * @property integer $txn_response_code
 * @property string $txn_response_ref
 * @property string $txn_platform_return_url
 * @property string $customer_ip
 */
class transactions extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'transactions';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



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

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'txn_amount' => 'integer',
        'txn_gateway_fee' => 'float',
        'txn_currency' => 'string',
        'txn_customer_id' => 'string',
        'txn_customer_name' => 'string',
        'txn_customer_email' => 'string',
        'txn_customer_mobile' => 'string',
        'txn_payment_type' => 'string',
        'txn_customer_bill_order_id' => 'string',
        'txn_reference' => 'string',
        'txn_gateway_options' => 'string',
        'txn_ec_platform_id' => 'integer',
        'txn_ec_gateway_id' => 'string',
        'txn_datetime' => 'datetime',
        'txn_expiry_datetime' => 'datetime',
        'txn_description' => 'string',
        'txn_status' => 'string',
        'txn_request' => 'string',
        'txn_response' => 'string',
        'txn_response_code' => 'integer',
        'txn_response_ref' => 'string',
        'txn_platform_return_url' => 'string',
        'customer_ip' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'txn_amount' => 'required|numeric',
        'txn_gateway_fee' => 'required|numeric',
        'txn_currency' => 'required|string|max:255',
        'txn_customer_id' => 'required|string',
        'txn_customer_name' => 'required|string',
        'txn_customer_email' => 'required|string|max:255',
        'txn_customer_mobile' => 'required|string|max:255',
        'txn_payment_type' => 'required|string|max:100',
        'txn_customer_bill_order_id' => 'required|string|max:100',
        'txn_reference' => 'required|string|max:100',
        'txn_gateway_options' => 'required|string|max:255',
        'txn_ec_platform_id' => 'required|integer',
        'txn_ec_gateway_id' => 'required|string|max:255',
        'txn_datetime' => 'required',
        'txn_expiry_datetime' => 'required',
        'txn_description' => 'required|string',
        'txn_status' => 'required|string',
        'txn_request' => 'nullable|string',
        'txn_response' => 'nullable|string',
        'txn_response_code' => 'nullable|integer',
        'txn_response_ref' => 'nullable|string|max:100',
        'txn_platform_return_url' => 'required|string',
        'customer_ip' => 'required|string|max:45',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
