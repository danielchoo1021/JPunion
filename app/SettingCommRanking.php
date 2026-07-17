<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingCommRanking extends Model
{
    protected $fillable = [
        'title', 'comm_requirement_limit', 'status'
    ];
}
