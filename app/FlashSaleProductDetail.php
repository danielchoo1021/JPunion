<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashSaleProductDetail extends Model
{
    protected $fillable = [
        'cart_link_id', 
        'product_id', 
        'variation_id', 
        'second_variation_id', 
        'qty', 
        'main_add_on', 
        'add_on_id',
        'mall',
        'promo',
        'promo_price_id', 
        'status'
    ];

    public function get_product_detail()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function get_variation()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }

    public function get_second_variation()
    {
        return $this->hasOne(ProductSecondVariation::class, 'id', 'second_variation_id');
    }

    public function get_prices()
    {
        return $this->hasMany(FlashSaleProductPrice::class, 'flash_sale_product_detail_id', 'id');
    }

    public function get_flash_sale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id', 'id');
    }
}
