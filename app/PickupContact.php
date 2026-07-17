<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PickupContact extends Model
{
    protected $fillable = [
        'transaction_id', 'f_name', 'email', 'phone', 'status', 'created_at', 'updated_at'
    ];
}
