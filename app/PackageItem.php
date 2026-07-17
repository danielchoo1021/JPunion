<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageItem extends Model
{
    protected $fillable = [
        'product_id', 'products', 'qty', 'unit_price', 'voucher_id', 'status'
    ];
}
