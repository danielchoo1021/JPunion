<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingWebsiteMessage extends Model
{
    protected $fillable = [
        'message', 'status'
    ];
}
