<?php

namespace App;

use App\Models\Account\TransactionDepositSlipFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    public $table = 'gateways';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'ec_pay_gateway_sort',
        'ec_pay_gateway_name',
        'ec_pay_gateway_url',
        'ec_pay_gateway_currency',
        'ec_pay_gateway_fee_percent',
        'ec_pay_gateway_image',
        'ec_pay_gateway_enabled',
        'ec_pay_gateway_description',
        'txn_gateway_options',
        'created_at',
        'updated_at',
    ];

    public function deposit_slip_fields()
    {
        return $this->hasMany(TransactionDepositSlipFields::class, 'gateway_id', 'id');
    }

    public function getGatewayNameUrlAttribute()
    {
        return $this->ec_pay_gateway_name . ' (' . $this->ec_pay_gateway_url . ')';
    }
}
