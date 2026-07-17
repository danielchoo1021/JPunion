<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiEInvoice extends Model
{
    protected $fillable = [
       'name', 'client_id', 'client_secret_1', 'client_secret_2', 'access_token', 'token_expiry', 'token_type', 'token_scope'
    ];
}