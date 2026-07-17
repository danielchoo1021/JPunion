<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_name', 'image', 'short_description', 'banner_image', 'status', 'merchant_id'
    ];
}
