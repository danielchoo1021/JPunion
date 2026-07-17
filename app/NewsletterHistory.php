<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterHistory extends Model
{
    protected $fillable = [
        'newsletter', 'status'
    ];
}
