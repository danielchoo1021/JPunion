<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id', 'item_code', 'product_code', 'product_id', 'unit_weight', 'second_sub_category', 'product_image', 'product_name', 'unit_price', 'product_comm_type', 'product_comm_amount', 'own_product_comm_type', 'own_product_comm_amount', 
        'quantity', 'total_amount', 'status', 'created_at', 'updated_at', 'upgrade_agent', 'product_name_en', 'level_up',
        'convert_pv'
    ];
}
