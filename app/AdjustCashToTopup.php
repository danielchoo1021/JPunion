<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustCashToTopup extends Model
{
    protected $table = 'adjust_cash_to_topup';

    protected $fillable = [
        'user_id', 'amount', 'status'
    ];
}
