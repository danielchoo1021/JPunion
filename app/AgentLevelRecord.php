<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentLevelRecord extends Model
{
    protected $fillable = [
        'user_id', 'level'
    ];
}
