<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPerformanceTier extends Model
{
    protected $fillable = [
        'target', 'amount', 'status'
    ];
}
