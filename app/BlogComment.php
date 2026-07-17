<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = [
        'blog_id', 'user_id', 'comment', 'blog_tags', 'status'
    ];
}
