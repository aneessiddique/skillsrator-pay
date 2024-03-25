<?php

namespace App;

use App\Models\Account\TransactionDepositSlipFieldsData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDepositSlip extends Model
{
    public $table = 'transaction_deposit_slip';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'txn_id',
        'gateway_id',
        'slip_url',
        'approved',
        'approved_by',
        'rejected',
        'rejected_by',
        'reject_reason',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'slip_url' => 'array',
    ];
    
    public function gateway()
    {
        return $this->hasOne(Gateway::class, 'id', 'gateway_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'txn_id');
    }
    
    public function approvedby()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
    
    public function rejectedby()
    {
        return $this->hasOne(User::class, 'id', 'rejected_by');
    }
    
    public function deposit_slip_fields_data(){
        return $this->hasMany(TransactionDepositSlipFieldsData::class, 'deposit_slip_id', 'id');
    }

}
