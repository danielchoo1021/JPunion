<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'user_id', 'type', 'transaction_no', 'product_name', 'product_qty', 'product_amount', 'comm_pa', 'comm_pa_type', 'comm_amount', 'comm_desc', 'status', 'claimed', 'burned',
        'user_by'
    ];
}
