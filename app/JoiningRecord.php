<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoiningRecord extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'bank_slip', 'transaction_no', 'status', 'package_id', 'bonus_amount'
    ];
}
