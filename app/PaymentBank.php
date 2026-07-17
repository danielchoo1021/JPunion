<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentBank extends Model
{
    protected $fillable = [
        'bank_name', 'bank_holder_name', 'bank_account', 'status'
    ];
}
