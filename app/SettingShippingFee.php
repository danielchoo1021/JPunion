<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingShippingFee extends Model
{
    protected $fillable = [
        'area', 'weight', 'shipping_fee', 'agent_shipping_fee', 'myr_shipping_fee', 'agent_myr_shipping_fee', 'ship_type', 'status',
        'country_id'
    ];
}
