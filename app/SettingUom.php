<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingUom extends Model
{
    protected $fillable = [
        'uom_name', 'merchant_id', 'status'
    ];
}
