<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPrizePool extends Model
{
    protected $fillable = [
        'position', 'type', 'amount', 'status'
    ];
}
