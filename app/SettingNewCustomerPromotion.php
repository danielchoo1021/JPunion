<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingNewCustomerPromotion extends Model
{
	protected $fillable = [
    	'active', 'promotion_title', 'image', 'discount_code', 'amount_type', 'amount', 'quantity', 'limit_type', 'usage_limit', 'products', 'duration', 'status'
    ];
}
