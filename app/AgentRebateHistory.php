<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentRebateHistory extends Model
{
    protected $fillable = [
        'user_id', 'commision_id', 'status'
    ];
}
