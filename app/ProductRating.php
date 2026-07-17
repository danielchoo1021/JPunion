<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'transaction_id', 'variation_id', 'rating', 'comment', 'status'
    ];
}
