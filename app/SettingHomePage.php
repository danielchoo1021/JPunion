<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingHomePage extends Model
{
    protected $fillable = [
        'image', 'title', 'description', 'description_cn', 'status'
    ];
}
