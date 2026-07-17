<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPrizePoolCondition extends Model
{
    protected $fillable = [
        'target', 'split_sales_percentage', 'type', 'status'
    ];
}
