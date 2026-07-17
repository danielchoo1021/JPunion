<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogView extends Model
{
    protected $fillable = [
        'user_id', 'blog_id'
    ];
}
