<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingPickUpAddress extends Model
{
    protected $fillable = [
        'company_name', 'contact', 'address', 'postcode', 'city', 'state', 'status'
    ];
}
