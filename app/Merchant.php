<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Merchant extends Authenticatable
{
    use Notifiable;
    protected $guard = 'merchant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'master_id', 'dual_master_id', 'code', 'email', 'password', 'f_name', 'l_name', 'gender', 'dob', 'phone', 'point', 'lvl', 'permission_lvl', 'profile_logo', 'agent_type', 'status', 'ic', 'prefer_language', 'cp', 'display_code', 'display_running_no', 'country_code', 'receive_newsletter',
        'verify_status',
        'register_transaction',
        'upload_ic',
        'joining_fee',
        'joining_fee_bank_slip',
        'ic_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function get_shipping_address()
    {
        return $this->hasMany(UserShippingAddress::class, 'user_id', 'code');
    }

    public function get_default_shipping_address()
    {
        return $this->hasOne(UserShippingAddress::class, 'user_id', 'code')->where('default', '1');
    }

    public function get_saved_vouchers()
    {
        return $this->hasMany(AppliedPromotion::class, 'user_id', 'code')->where('status', '99');
    }

    public function get_applied_voucher()
    {
        return $this->hasOne(AppliedPromotion::class, 'user_id', 'code')->where('status', '1')->orderBy('created_at', 'desc');
    }

    public function get_upline_det()
    {
        return $this->hasOne(Affiliate::class, 'affiliate_id', 'code')->where('sort_level', '1');
    }

    public function get_level()
    {
        return $this->hasOne(AgentLevel::class, 'id', 'lvl');
    }
}
