<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddOnDealSubItem extends Model
{
    protected $fillable = [
       'add_on_id','add_on_item_id','product_id','variation_id','add_on_price','add_on_discount','purchase_limit','status','second_variation_id'
    ];

    public function get_product_det()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
