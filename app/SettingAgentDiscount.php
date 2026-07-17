<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingAgentDiscount extends Model
{
    protected $fillable = [
        'type', 'amount'
    ];
}
