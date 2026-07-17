<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiPartner extends Model
{
    protected $fillable = [
       'partner_id', 'partner_name','partner_email','partner_key','status',
    ];
}
