<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingBanner extends Model
{
    protected $fillable = [
        'image', 'url', 'sort_level', 'merchant_id'
    ];
}
