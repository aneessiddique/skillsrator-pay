<?php

namespace App\Models\Account;

use Eloquent as Model;



/**
 * Class TransactionDepositSlipFieldsData
 * @package App\Models\Account
 * @version April 2, 2022, 1:40 pm UTC
 *
 * @property integer $deposit_slip_id
 * @property integer $field_id
 * @property string $field_value
 * @property integer $txn_id
 */
class TransactionDepositSlipFieldsData extends Model
{


    public $table = 'transaction_deposit_slip_fields_data';
    

    public $fillable = [
        'deposit_slip_id',
        'field_id',
        'field_value',
        'txn_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'deposit_slip_id' => 'integer',
        'field_id' => 'integer',
        'field_value' => 'string',
        'txn_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function deposit_slip_fields(){
        return $this->hasOne(TransactionDepositSlipFields::class, 'id', 'field_id');
    }
}
