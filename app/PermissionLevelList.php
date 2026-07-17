<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionLevelList extends Model
{
    protected $fillable = [
        'name', 'status'
    ];
}
