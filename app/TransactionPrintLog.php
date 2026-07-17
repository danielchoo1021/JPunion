<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionPrintLog extends Model
{
    protected $fillable = [
        'transaction_id', 'transaction_no', 'document_type', 'printer_name', 'attempt', 'status', 'message',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
