<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'sub_category_id', 'second_sub_category_id', 'qty', 'status', 'mall',
        'promo', 'promo_price_id', 'main_add_on', 'add_on_id', 'flash_sale_product_id', 'remark', 'cashier','is_birthday'
    ];

    public function get_product_det()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function get_fv_det()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'sub_category_id');
    }

    public function get_sv_det()
    {
        return $this->hasOne(ProductSecondVariation::class, 'id', 'second_sub_category_id');
    }

    public function get_promo_items()
    {
        return $this->hasMany(PromoAgentItemDetail::class, 'promo_item_id', 'promo_price_id');
    }

    public function get_flash_sale_product_detail()
    {
        return $this->hasOne(FlashSaleProductDetail::class, 'id', 'flash_sale_product_id');
    }
}
