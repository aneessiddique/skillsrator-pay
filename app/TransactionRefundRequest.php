<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionRefundRequest extends Model
{
    public $table = 'transaction_refund_requests';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'txn_id',
        'gateway_id',
        'approved',
        'approved_by',
        'approved_reason',
        'rejected',
        'rejected_by',
        'reject_reason',
        'created_at',
        'updated_at',
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

}
