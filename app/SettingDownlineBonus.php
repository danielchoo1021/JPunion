<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingDownlineBonus extends Model
{
    protected $fillable = [
        'level_id', 'target', 'comm_type', 'comm_amount', 'status'
    ];
}
