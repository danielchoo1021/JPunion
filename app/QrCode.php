<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
    	'table_id', 'status'
    ];
}
