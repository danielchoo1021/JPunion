<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackendHistory extends Model
{
    protected $fillable = [
        'description', 'created_by', 'status'
    ];
}