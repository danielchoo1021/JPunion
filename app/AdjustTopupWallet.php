<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustTopupWallet extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'remark', 'status', 'created_by', 'updated_by'
    ];
}
