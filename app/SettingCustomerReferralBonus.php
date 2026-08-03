<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCustomerReferralBonus extends Model
{
    protected $fillable = [
        'agent_lvl', 'target_orders', 'amount'
    ];
}
