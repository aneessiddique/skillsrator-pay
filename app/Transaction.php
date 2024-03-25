<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public $table = 'transactions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'txn_ec_gateway_id',
        'txn_response_ref',
        'txn_expiry_datetime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    public function gateway()
    {
        return $this->hasOne(Gateway::class, 'id', 'txn_ec_gateway_id')->withDefault(['ec_pay_gateway_name' => '']);
    }
    
    public function platform()
    {
        return $this->hasOne(ApiKey::class, 'id', 'txn_ec_platform_id')->withDefault(['ec_pay_app_name' => '']);
    }
}
