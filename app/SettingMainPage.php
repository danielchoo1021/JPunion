<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingMainPage extends Model
{
    protected $fillable = [
        'image', 'title', 'description', 'sort_level'
    ];
}
