<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'image', 'title', 'description', 'blog_date', 'blog_tags', 'status', 'type', 'title_cn', 'description_cn', 'blog_tags_cn'
    ];
}

