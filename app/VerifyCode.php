<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    protected $fillable = [
        'phone', 'code', 'status'
    ];
}
