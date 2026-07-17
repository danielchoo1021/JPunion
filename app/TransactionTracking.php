<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTracking extends Model
{
    protected $fillable = [
        'row_id', 'transaction_id', 'tracking_no', 'order_number', 'parcel_number', 'courier', 'courier_logo', 'status',
        'remarks', 'ship_status'
    ];
}
