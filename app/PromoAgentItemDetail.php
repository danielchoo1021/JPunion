<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoAgentItemDetail extends Model
{
    protected $fillable = [
    	'promo_item_id', 'variation_id', 'second_variation_id', 'agent_lvl_id', 'price', 'special_price', 'status'
    ];
}
