<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'menu_bar', 'code', 'category_name', 'status', 'merchant_id'
    ];

    public function get_sub_categories()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id')->whereStatus(1);
    }
}
