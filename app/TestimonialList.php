<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestimonialList extends Model
{
    protected $fillable = [
        'name', 'code', 'transaction_no', 'gave', 'promotion_id', 'image', 'upload_by'
    ];

    protected $table = 'testimonial_lists';
}