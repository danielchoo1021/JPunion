<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingHomeVideo extends Model
{
    protected $fillable = [
        'image', 'description', 'description_cn', 'status'
    ];
}
