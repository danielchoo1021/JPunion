<?php

namespace App\Console\Commands;

use App\Services\OrderPrintService;
use App\Transaction;
use Illuminate\Console\Command;

class TestOrderPrint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:test-print {transaction_no? : Transaction number to print; latest transaction if omitted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually trigger the auto-print flow (A4 invoice + packing slip) for a transaction, without needing a real order.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // The app's global view composer (AuthServiceProvider) reads
        // $_SERVER['SERVER_NAME'] on every view render; that key only exists
        // inside a real HTTP request, not when running via `artisan` on the
        // CLI. Fake it here so this test command can render the same Blade
        // views the real (HTTP-triggered) auto-print flow will use.
        $_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? '127.0.0.1';

        // Same composer also include()s a language file using a path
        // relative to the working directory, which is `public/` under
        // Apache but the project root under `artisan`. Match Apache's CWD
        // so the same include path resolves during this CLI test.
        $originalCwd = getcwd();
        chdir(public_path());

        $transactionNo = $this->argument('transaction_no');

        $transaction = $transactionNo
            ? Transaction::where('transaction_no', $transactionNo)->first()
            : Transaction::orderBy('id', 'desc')->first();

        if (!$transaction) {
            $this->error('No transaction found.');
            return 1;
        }

        $this->info("Printing transaction {$transaction->transaction_no} (status={$transaction->status})...");

        (new OrderPrintService())->printOrder($transaction);

        $this->info('Done. Check the transaction_print_logs table for the result of each printer.');
        chdir($originalCwd);
        return 0;
    }
}
