<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerLevel extends Model
{
    protected $fillable = [
        'customer_lvl', 'customer_lvl_code', 'joining_fee', 'status'
    ];
}
