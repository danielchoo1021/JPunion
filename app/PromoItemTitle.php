<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoItemTitle extends Model
{
    protected $fillable = [
    	'promo_title', 'date_from', 'date_end', 'customer_price', 'customer_special_price', 'status', 'sorting'
    ];
}
