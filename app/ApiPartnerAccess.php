<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiPartnerAccess extends Model
{
    protected $fillable = [
       'api_partners_id','access_token','refresh_token','expire_at',
    ];
}
