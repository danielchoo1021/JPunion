<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariationStock extends Model
{
    protected $fillable = [
        'product_id', 'variation_id', 'quantity'
    ];
}
