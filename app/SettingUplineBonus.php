<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingUplineBonus extends Model
{
    protected $fillable = [
        'agent_lvl', 'point_target', 'type', 'amount', 'status'
    ];
}
