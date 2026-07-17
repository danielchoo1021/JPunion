<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppliedPromotion extends Model
{
    protected $fillable = [
        'promotion_id', 'user_id', 'is_assign', 'assign_by', 'remark', 'promotion_title', 'image', 'discount_code', 'amount_type', 'amount', 'transaction_id', 'status', 'product_voucher'
    ];

    public function get_voucher_detail()
    {
        return $this->hasOne(Promotion::class, 'id', 'promotion_id');
    }

    public function admin() {
        return $this->belongsTo(Admin::class, 'assign_by', 'code');
    }

    public function userByCode()
    {
        return $this->belongsTo(User::class, 'user_id', 'code');
    }

    public function agentByCode()
    {
        return $this->belongsTo(Agent::class, 'user_id', 'code');
    }
}
