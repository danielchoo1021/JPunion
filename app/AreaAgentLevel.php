<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaAgentLevel extends Model
{
    protected $fillable = [
        'area_agent_lvl', 'area_agent_lvl_cn', 'subsidy', 'status'
    ];
}
