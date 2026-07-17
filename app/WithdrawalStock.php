<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class WithdrawalStock extends Model
{
    protected $fillable = [
        'transaction_no', 
        'user_id',
        'product_id',
        'variation_id',
        'second_variation_id',
        'quantity',
        'shipping_fee',
        'bank_slip',
        'status',
        'user_shipping_address_id',
        'awb_no',
        'order_number',
        'tracking_no',
        'parcel_number',
        'courier',
        'courier_logo'
    ];

    public function get_state()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function get_coutry()
    {
        return $this->hasOne(TblCountry::class, 'country_id', 'country');
    }

    public function get_stock_details()
    {
        return $this->hasMany(WithdrawalStockDetail::class, 'withdrawal_stock_id', 'id');
    }

    public function get_cod_address()
    {
        return $this->hasOne(CodAddress::class, 'id', 'cod_address');
    }
}
