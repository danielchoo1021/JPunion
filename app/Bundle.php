<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
        'bundle_name', 'bundle_description', 'bundle_price', 'bundle_agent_price', 'status'
    ];
}
