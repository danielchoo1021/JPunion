<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id', 'affiliate_id', 'sort_level', 'status'
    ];

    public function get_user_id_agent_det()
    {
        return $this->hasOne(Agent::class, 'code', 'user_id');
    }

    public function get_user_id_member_det()
    {
        return $this->hasOne(User::class, 'code', 'user_id');
    }

    public function get_user_id_admin_det()
    {
        return $this->hasOne(Admin::class, 'code', 'user_id');
    }
}
