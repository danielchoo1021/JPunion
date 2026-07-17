<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingMerchantBonus extends Model
{
    protected $fillable = [
        'type', 'amount', 'status'
    ];
}
