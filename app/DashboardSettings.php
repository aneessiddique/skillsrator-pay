<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DashboardSettings extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'settings';
    protected $guarded = [''];
}
