<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariationTitle extends Model
{
    protected $fillable = [
        'product_id', 'title', 'status', 'created_at', 'updated_at'
    ];
}
