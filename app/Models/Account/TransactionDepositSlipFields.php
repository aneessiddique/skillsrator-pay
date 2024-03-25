<?php

namespace App\Models\Account;

use Eloquent as Model;



/**
 * Class TransactionDepositSlipFields
 * @package App\Models\Account
 * @version April 2, 2022, 1:38 pm UTC
 *
 * @property integer $gateway_id
 * @property string $field_name
 * @property string $field_type
 */
class TransactionDepositSlipFields extends Model
{


    public $table = 'transaction_deposit_slip_fields';

    public $fillable = [
        'gateway_id',
        'field_name',
        'field_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'gateway_id' => 'integer',
        'field_name' => 'string',
        'field_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
