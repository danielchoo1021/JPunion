<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateDual extends Model
{
    protected $fillable = [
        'user_id', 'affiliate_id', 'status'
    ];
}
