<?php

namespace App\Observers;

use App\Services\OrderPrintService;
use App\Transaction;

class TransactionObserver
{
    /**
     * Covers orders that are paid at creation time (e.g. cash/topup wallet
     * checkout, which sets status=1 before the first save()).
     */
    public function created(Transaction $transaction)
    {
        if ((int) $transaction->status === 1) {
            $this->printOrder($transaction);
        }
    }

    /**
     * Covers orders that become paid later (gateway callbacks, admin bank
     * slip verification, etc.) - fires exactly once, when status actually
     * transitions to 1 on that save() call.
     */
    public function updated(Transaction $transaction)
    {
        if ($transaction->wasChanged('status') && (int) $transaction->status === 1) {
            $this->printOrder($transaction);
        }
    }

    protected function printOrder(Transaction $transaction)
    {
        (new OrderPrintService())->printOrder($transaction);
    }
}
