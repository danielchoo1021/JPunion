<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Distributor extends Authenticatable
{
    use Notifiable;
    protected $guard = 'distributor';

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
}
