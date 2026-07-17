<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingMerchantSameLevel extends Model
{
    protected $fillable = [
        'agent_lvl', 'level', 'comm_type', 'comm_amount', 'status'
    ];
}
