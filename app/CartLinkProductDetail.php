<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartLinkProductDetail extends Model
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

    public function get_product_det()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function get_fv_det()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }

    public function get_sv_det()
    {
        return $this->hasOne(ProductSecondVariation::class, 'id', 'second_variation_id');
    }

    public function get_promo_items()
    {
        return $this->hasMany(PromoAgentItemDetail::class, 'promo_item_id', 'promo_price_id');
    }
}
