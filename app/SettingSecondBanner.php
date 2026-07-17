<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingSecondBanner extends Model
{
    protected $fillable = [
        'image', 'url', 'sort_level', 'merchant_id'
    ];
}
