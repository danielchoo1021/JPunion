<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionQrPayment extends Model
{
    protected $fillable = [
        'payment_no', 'merchant_id', 'user_id', 'amount',
        'charges_type',
        'charges_amount',
        'status'
    ];
}
