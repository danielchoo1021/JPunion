<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingWebsiteImage extends Model
{
    protected $fillable = [
        'image', 'sort_level'
    ];
}
