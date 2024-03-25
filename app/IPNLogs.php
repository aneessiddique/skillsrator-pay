<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPNLogs extends Model
{
    // use HasFactory;
    public $table = 'ipn_log';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'ipn_gateway',
        'ipn_response',
        'created_at',
        'updated_at',
    ];
}
