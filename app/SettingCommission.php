<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCommission extends Model
{
    protected $fillable = [
        'user_type', 'level', 'comm_type', 'comm_amount', 'agent_level', 'status'
    ];
}
