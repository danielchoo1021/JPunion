<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'category_id', 'merchant_id', 'sub_category_code', 'sub_category_name', 'status'
    ];
}
