<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingDualMain extends Model
{
    protected $fillable = [
        'comm_type', 'comm_amount', 'status'
    ];
}
