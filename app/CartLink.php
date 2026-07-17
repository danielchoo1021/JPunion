<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartLink extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'sub_category_id', 'second_sub_category_id', 'qty', 'status', 'unique_id', 'price'
    ];
}
