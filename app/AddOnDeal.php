<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddOnDeal extends Model
{
    protected $fillable = [
       'promotion_name','start_date','end_date','purchase_limit','status'
    ];
}
