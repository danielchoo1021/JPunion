<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddOnDealItem extends Model
{
    protected $fillable = [
       'add_on_id', 'product_id', 'variation_id', 'second_variation_id', 'price', 'status'
    ];

    public function get_product_det()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
