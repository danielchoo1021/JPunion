<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    protected $fillable = [
        'topup_no', 'user_id', 'amount', 'topup_payment_method', 'bank_slip', 'status', 'package_id', 'amount_desc',
        'actual_amount'
    ];
}
