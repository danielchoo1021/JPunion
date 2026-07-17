<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoAgentItem extends Model
{
    protected $fillable = [
    	'title_id', 'product_id', 'price', 'special_price', 'status'
    ];
}
