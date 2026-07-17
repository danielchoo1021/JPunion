<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashSaleProductPrice extends Model
{
    protected $fillable = [
        'product_id', 'variation_id', 'second_variation_id', 'agent_lvl_id', 'price', 'special_price', 'status'
    ];

    public function get_flash_sale_product_detail()
    {
        return $this->hasOne(FlashSaleProductDetail::class, 'id', 'flash_sale_product_detail_id');
    }
}
