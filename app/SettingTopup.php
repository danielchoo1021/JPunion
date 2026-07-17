<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingTopup extends Model
{
    protected $fillable = [
        'topup_amount', 'profit_type', 'profit_amount'
    ];
}
