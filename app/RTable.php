<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RTable extends Model
{
    protected $fillable = [
    	'table_name', 'status'
    ];
}
