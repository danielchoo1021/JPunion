<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingAgentReferralPeriod extends Model
{
    protected $fillable = [
        'agent_lvl', 'month_period'
    ];
}
