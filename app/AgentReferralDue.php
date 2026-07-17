<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentReferralDue extends Model
{
    protected $fillable = [
        'user_id', 'level', 'due_date', 'status'
    ];
}
