<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentPrice extends Model
{
    protected $fillable = [
        'product_id', 'variation_id', 'second_variation_id', 'agent_lvl_id', 'price', 'special_price', 'birthday_price', 'birthday_special_price','status'
    ];
}
