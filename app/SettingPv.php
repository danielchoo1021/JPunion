<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPv extends Model
{
    protected $fillable = [
        'get_pv_rate', 'spend_pv_rate', 'status'
    ];
}
