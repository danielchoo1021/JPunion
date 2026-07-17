<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundleDetail extends Model
{
    protected $fillable = [
        'bundle_id', 'product_id', 'status'
    ];
}
