<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberPv extends Model
{
    protected $fillable = [
        'user_id', 'pv_amount', 'month_period', 'status', 'transaction_no', 'user_by', 'recruiting_transaction'
    ];
}
