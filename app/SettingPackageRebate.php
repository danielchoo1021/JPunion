<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPackageRebate extends Model
{
    protected $fillable = [
        'agent_lvl', 'point_target', 'type', 'amount', 'status'
    ];
}
