<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgetPasswordRecord extends Model
{
    protected $fillable = [
        'code', 'link', 'link_used', 'status'
    ];
}
