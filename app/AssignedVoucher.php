<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignedVoucher extends Model
{
    protected $fillable = [
        'promotion_id', 'user_id', 'quantity', 'remark', 'created_at', 'created_by', 'updated_by'
    ];

    public function user() {
    return $this->belongsTo(User::class, 'user_id');
    }
    public function admin() {
        return $this->belongsTo(Admin::class, 'created_by');
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
