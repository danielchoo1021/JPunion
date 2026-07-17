<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrPayList extends Model
{
    protected $fillable = [
    	'title', 'image', 'status'
    ];
}
