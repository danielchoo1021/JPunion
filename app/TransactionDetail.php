<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'item_code', 'product_code', 'product_id', 'unit_weight', 'second_sub_category', 'product_image', 'product_name', 'unit_price', 'product_comm_type', 'product_comm_amount', 'own_product_comm_type', 'own_product_comm_amount', 
        'quantity', 'total_amount', 'status', 'created_at', 'updated_at', 'upgrade_agent', 'product_name_en', 'level_up',
        'convert_pv',
        'voucher_id',
        'merge_from_transaction',
        'promo_item_id',
        'get_pv',
        'main_add_on',
        'add_on_id',
        'flash_sale_product_price_id',
        'product_name_cn','is_birthday'
    ];

    public function get_packages()
    {
        return $this->hasMany(TransactionPackage::class, 'detail_id');
    }

    public function get_promo_title()
    {
        return $this->hasOne(PromoItemTitle::class, 'id', 'promo_item_id');
    }

    public function get_product_details()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
