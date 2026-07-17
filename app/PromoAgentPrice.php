<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoAgentPrice extends Model
{
    protected $fillable = [
    	'item_id', 'agent_lvl_id', 'price', 'status'
    ];
}
