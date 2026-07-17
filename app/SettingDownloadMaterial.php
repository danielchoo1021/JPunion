<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingDownloadMaterial extends Model
{
    protected $fillable = [
        'file_name', 'type_id', 'images', 'status'
    ];
}
