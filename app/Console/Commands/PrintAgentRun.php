<?php

namespace App\Console\Commands;

use App\Services\LocalPdfPrinter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Runs on the machine that has the invoice/label printers physically
 * attached (not necessarily the machine hosting the site - see
 * config/printing.php 'mode' = 'queue'). Polls the hosted site's
 * /api/print-agent/queue endpoint for paid orders that still need printing,
 * downloads the rendered PDF, and prints it locally with SumatraPDF -
 * exactly what OrderPrintService does when it runs on the same machine as
 * the site, just triggered by polling instead of a same-process event.
 *
 * Intended to be left running (e.g. a Windows Task Scheduler job "at log
 * on", or wrapped in a restart-on-crash .bat loop), not run once.
 */
class PrintAgentRun extends Command
{
    protected $signature = 'print:agent {--once : Run a single poll cycle and exit, instead of looping forever}';

    protected $description = 'Poll the hosted site for orders awaiting print and print them on this machine\'s local printers.';

    public function handle()
    {
        $remoteUrl = rtrim((string) config('printing.remote_url'), '/');
        $token = (string) config('printing.agent_token');
        $pollSeconds = max(1, (int) config('printing.poll_seconds', 5));

        if (empty($remoteUrl) || empty($token)) {
            $this->error('PRINT_AGENT_REMOTE_URL and PRINT_AGENT_TOKEN must be set in .env before running this command.');
            return 1;
        }

        $this->info("Polling {$remoteUrl} every {$pollSeconds}s. Ctrl+C to stop.");

        do {
            try {
                $this->pollOnce($remoteUrl, $token);
            } catch (\Throwable $e) {
                $this->error('Poll failed: ' . $e->getMessage());
            }

            if (!$this->option('once')) {
                sleep($pollSeconds);
            }
        } while (!$this->option('once'));

        return 0;
    }

    protected function pollOnce(string $remoteUrl, string $token): void
    {
        $response = Http::withHeaders(['X-Print-Agent-Token' => $token])
            ->timeout(15)
            ->get("{$remoteUrl}/api/print-agent/queue");

        if (!$response->successful()) {
            $this->error('Queue request failed: HTTP ' . $response->status());
            return;
        }

        $jobs = $response->json('jobs', []);

        foreach ($jobs as $job) {
            $this->printJob($remoteUrl, $token, $job);
        }
    }

    protected function printJob(string $remoteUrl, string $token, array $job): void
    {
        $transactionId = $job['transaction_id'];
        $transactionNo = $job['transaction_no'];
        $documentType = $job['document_type'];
        $attempt = $job['attempt'];

        // The local printer names are this machine's own config, not
        // whatever the remote site's .env happens to say - the two only
        // need to agree by convention, and this machine is the one that
        // actually knows what's plugged in.
        $printerName = $documentType === 'invoice_a4'
            ? config('printing.invoice_printer')
            : config('printing.label_printer');
        $orientation = $documentType === 'packing_label' ? 'landscape' : 'portrait';

        $this->info("Printing {$documentType} for {$transactionNo} (attempt {$attempt}) on \"{$printerName}\"...");

        try {
            $pdfResponse = Http::withHeaders(['X-Print-Agent-Token' => $token])
                ->timeout(30)
                ->get("{$remoteUrl}/api/print-agent/pdf/{$transactionId}/{$documentType}");

            if (!$pdfResponse->successful()) {
                throw new \RuntimeException("PDF download failed: HTTP {$pdfResponse->status()}");
            }

            $tempDir = storage_path('app/print_jobs');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $filePath = $tempDir . DIRECTORY_SEPARATOR . $documentType . '_' . $transactionNo . '_' . time() . '.pdf';
            file_put_contents($filePath, $pdfResponse->body());

            $process = (new LocalPdfPrinter())->print($filePath, $printerName, $orientation);
            $process->wait();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException("SumatraPDF exited with error code {$process->getExitCode()}");
            }

            $this->ack($remoteUrl, $token, $transactionId, $documentType, $printerName, $attempt, 'success', null);
        } catch (\Throwable $e) {
            $this->error("Failed: {$e->getMessage()}");
            $this->ack($remoteUrl, $token, $transactionId, $documentType, $printerName, $attempt, 'failed', $e->getMessage());
        }
    }

    protected function ack(string $remoteUrl, string $token, int $transactionId, string $documentType, string $printerName, int $attempt, string $status, ?string $message): void
    {
        Http::withHeaders(['X-Print-Agent-Token' => $token])
            ->timeout(15)
            ->post("{$remoteUrl}/api/print-agent/ack/{$transactionId}/{$documentType}", [
                'status' => $status,
                'printer_name' => $printerName,
                'attempt' => $attempt,
                'message' => $message,
            ]);
    }
}
