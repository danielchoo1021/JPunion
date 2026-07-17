<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'title', 'start', 'end', 'status'
    ];

    public function get_flash_product_details()
    {
    	return $this->hasMany(FlashSaleProductDetail::class, 'flash_sale_id', 'id');
    }
}
