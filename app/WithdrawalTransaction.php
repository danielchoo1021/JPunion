<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawalTransaction extends Model
{
    protected $fillable = [
        'withdrawal_no', 'user_id', 'bank_name', 'bank_holder_name', 'bank_account', 'amount', 'withdrawal_slip', 'status',
        'actual_amount', 'company_charges', 'merchant_id'
    ];
}
