<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingJoiningFee extends Model
{
    protected $fillable = [
        'target', 'comm_type', 'comm_amount', 'month_period', 'status'
    ];
}
