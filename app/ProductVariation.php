<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id', 'variation_name', 'variation_price', 'variation_special_price', 'variation_agent_price', 'variation_agent_special_price', 'variation_weight', 'variation_sku', 'status', 'second_variation_enable', 'variation_stock', 'variation_costing_price',
        'variation_west_price', 'variation_west_special_price', 'variation_corporate_price', 'variation_corporate_special_price',
        'variation_corporate_moq',
        'variation_get_point',
        'variation_get_pv',
        'variation_birthday_price',
        'variation_birthday_special_price',
        'variation_image'
    ];

    public function get_agent_price_variation()
    {
        return $this->belongsTo(AgentPrice::class, 'id', 'variation_id');
    }

    public function get_second_variations()
    {
        return $this->hasMany(ProductSecondVariation::class, 'variation_id', 'id');
    }
}
