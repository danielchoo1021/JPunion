<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodAddress extends Model
{
    protected $fillable = [
        'address', 'address_desc', 'status', 'cod_code'
    ];
}
