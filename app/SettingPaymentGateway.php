<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPaymentGateway extends Model
{
    protected $table = 'setting_payment_gateway';
    
    protected $fillable = [
        'position', 'type', 'amount', 'status'
    ];

    public static function get_senangpay()
    {
        return self::find(1);
    }

    public static function get_revpay()
    {
        return self::find(2);
    }

    public static function get_surepay()
    {
        return self::find(3);
    }

    public static function get_gkash()
    {
        return self::find(4);
    }
}
