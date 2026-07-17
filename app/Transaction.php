<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'guest_agent', 'transaction_no', 'user_id', 'discount_code', 'discount', 'processing_fee', 'tax', 'shipping_fee', 'grand_total', 
        'sub_total', 'address_name', 'address', 'postcode', 'city', 'state', 'country_code', 'phone', 'email', 'mall', 'bank_id', 'cdm_bank_id', 'bank_slip', 
        'bank_slip_no', 'bank_slip_date', 'status', 'completed', 'ad_discount', 'different_billing_address',
        'customer_address', 'c_address_name', 'c_address', 'c_postcode', 'c_city', 'c_state', 'c_phone', 'c_email', 
        'parcel_number', 'order_number', 'courier', 'courier_logo', 'tracking_no', 'weight', 'awb_no', 'to_receive',
        'cod_address', 'viewed', 'country', 'on_hold', 'online_payment_method', 'bank_name', 'card_holder_name',
        'card_mask', 'card_exp', 'card_type', 'cancelled_by',
        'pv_purchase', 'ad_discount_type', 'ad_discount_amount',
        'delivery_pickup_datetime',
        'register_product',
        'recruiting_product',
        'merge_transaction',
        'cc_bank_id',
        'qr_pay_id',
        'created_backend',
        'ship_type',
        'order_type'
    ];

    public function get_transaction_details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    public function get_transaction_details_with_same_level()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id')->where('same_level_bonus', '>', 0);
    }

    public function get_transaction_details_with_upgrade_level()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id')->where('level_up', '>', 0)->orderBy('level_up', 'asc');
    }

    public function get_transaction_cart_link()
    {
        return $this->hasOne(CartLink::class, 'id', 'cart_link_id');
    }
    
    public function get_country()
    {
        return $this->hasOne(TblCountry::class, 'country_id', 'country');
    }

    public function get_state()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function get_bank(){
         return $this->hasOne(PaymentBank::class, 'id', 'cc_bank_id');
    }

    public function qr_paylist(){
         return $this->hasOne(QrPayList::class, 'id', 'qr_pay_id');
    }

    public function get_payment_gateway_setting()
    {
        return $this->hasOne(SettingPaymentGateway::class, 'id', 'payment_gateway_setting_id');
    }
}
