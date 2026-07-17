<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingTeamDividend extends Model
{
    protected $fillable = [
        'target_box', 'amount'
    ];
}
