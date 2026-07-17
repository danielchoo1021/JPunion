<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingMaterial extends Model
{
    protected $fillable = [
        'type_id', 'images', 'status'
    ];
}
