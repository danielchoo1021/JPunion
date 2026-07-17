<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingLooBonus extends Model
{
    protected $fillable = [
        'target', 'comm_type', 'comm_amount', 'status'
    ];
}
