<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingFeedback extends Model
{
    protected $fillable = [
        'products', 'title', 'status'
    ];
}
