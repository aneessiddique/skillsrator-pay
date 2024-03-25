<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    public $table = 'api_keys';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'ec_pay_app_name',
        'ec_pay_api_key',
        'ec_pay_api_iv',
        'ec_pay_api_token',
        'created_at',
        'updated_at',
    ];
}
