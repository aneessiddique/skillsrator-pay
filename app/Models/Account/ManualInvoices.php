<?php

namespace App\Models\Account;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class ManualInvoices
 * @package App\Models\Account
 * @version April 15, 2022, 6:34 am UTC
 *
 * @property integer $inv_amount
 * @property string $inv_currency
 * @property string $inv_customer_id
 * @property string $inv_customer_name
 * @property string $inv_customer_email
 * @property string $inv_customer_mobile
 * @property string $inv_payment_type
 * @property string $inv_customer_bill_order_id
 * @property string $inv_gateway_options
 * @property string $inv_description
 * @property string $inv_platform_return_url
 */
class ManualInvoices extends Model
{
    use SoftDeletes;


    public $table = 'manual_invoices';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'txn_amount',
        'txn_currency',
        'txn_customer_id',
        'txn_customer_name',
        'txn_customer_email',
        'txn_customer_mobile',
        'txn_payment_type',
        'txn_customer_bill_order_id',
        'txn_gateway_options',
        'txn_description',
        'txn_expiry_datetime',
        'txn_platform_return_url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'txn_amount' => 'integer',
        'txn_currency' => 'string',
        'txn_customer_id' => 'string',
        'txn_customer_name' => 'string',
        'txn_customer_email' => 'string',
        'txn_customer_mobile' => 'string',
        'txn_payment_type' => 'string',
        'txn_customer_bill_order_id' => 'string',
        'txn_gateway_options' => 'string',
        'txn_description' => 'string',
        'txn_platform_return_url' => 'string',
        'txn_expiry_datetime' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'txn_amount' => 'required|numeric',
        'txn_currency' => 'required|string|max:255',
        'txn_customer_id' => 'required|string',
        'txn_customer_name' => 'required|string',
        'txn_customer_email' => 'required|string|max:255',
        'txn_customer_mobile' => 'required|string|max:255',
        'txn_payment_type' => 'required|string|max:100',
        // 'txn_customer_bill_order_id' => 'required|string|max:100',
        // 'txn_gateway_options' => 'required|string',
        'txn_description' => 'required|string',
        // 'txn_platform_return_url' => 'required|string',
        // 'txn_expiry_datetime' => 'datetime',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
