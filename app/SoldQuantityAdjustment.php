<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldQuantityAdjustment extends Model
{
    protected $fillable = [
        'product_id', 'type', 'quantity', 'packages_id', 'remark', 'status'
    ];

    public function get_variation_det()
    {
        return $this->hasOne(ProductVariation::class, 'id', 'variation_id');
    }

    public function get_second_variation_det()
    {
        return $this->hasOne(ProductSecondVariation::class, 'id', 'second_variation_id');
    }
}
