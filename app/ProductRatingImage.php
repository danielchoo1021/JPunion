<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRatingImage extends Model
{
    protected $fillable = [
        'rating_id', 'image', 'sort_level', 'status'
    ];
}
