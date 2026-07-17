<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesPopup extends Model
{
	protected $fillable = [
    	'name', 'product_id', 'sales_date', 'status'
    ];
}
