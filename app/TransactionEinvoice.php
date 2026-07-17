<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionEinvoice extends Model
{
    protected $fillable = [
        'transaction_no', 'einvoice_uuid', 'status', 'api_response'
    ];
}
