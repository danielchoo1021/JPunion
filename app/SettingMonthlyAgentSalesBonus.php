<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingMonthlyAgentSalesBonus extends Model
{
    protected $fillable = [
        'monthly_type', 'target', 'comm_type', 'comm_amount', 'status'
    ];
}
