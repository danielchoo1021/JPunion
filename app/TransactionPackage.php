<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionPackage extends Model
{
    protected $fillable = [
        'detail_id', 'product_id', 'variation_id', 'second_variation_id', 'voucher_id', 'quantity', 'status'
    ];
}
