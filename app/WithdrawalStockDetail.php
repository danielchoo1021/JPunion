<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawalStockDetail extends Model
{
    protected $fillable = [
    ];

    public function get_product_det()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function get_variation_det()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }

    public function get_second_variation_det()
    {
        return $this->hasOne(ProductSecondVariation::class, 'id', 'second_variation_id');
    }
}
