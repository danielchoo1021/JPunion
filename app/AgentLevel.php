<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentLevel extends Model
{
    protected $fillable = [
        'agent_lvl', 'agent_lvl_cn', 'product_id', 'buy_quantity', 'affiliate_quantity', 'month', 'target', 'agent_discount_type', 'agent_discount', 'joining_fee', 'status'
    ];
}
