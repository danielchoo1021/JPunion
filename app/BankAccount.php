<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'user_id', 'bank_name', 'bank_holder_name', 'bank_account', 'default_banks', 'status'
    ];
}
