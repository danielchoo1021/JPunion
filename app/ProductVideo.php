<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    protected $fillable = [
        'product_id', 'image', 'sort_level', 'status'
    ];
}
