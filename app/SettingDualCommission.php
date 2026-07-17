<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingDualCommission extends Model
{
    protected $fillable = [
        'agent_lvl', 'comm_type', 'comm_amount', 'status'
    ];
}
