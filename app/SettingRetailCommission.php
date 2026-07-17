<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingRetailCommission extends Model
{
    protected $fillable = [
        'target', 'comm_type', 'comm_amount', 'month_period', 'status'
    ];
}
