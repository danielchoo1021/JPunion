<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionBillingAddress extends Model
{
    protected $fillable = [
        'transaction_id', 'address_name', 'address', 'postcode', 'city', 'state', 'country_code', 'phone', 'email', 'status'
    ];
}
