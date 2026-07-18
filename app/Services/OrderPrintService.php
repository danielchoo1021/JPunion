<?php

namespace App\Services;

use App\Admin;
use App\State;
use App\TblCountry;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionPrintLog;
use App\WebsiteSetting;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class OrderPrintService
{
    /**
     * Print the A4 invoice + 4x6 packing slip for a paid order.
     * Both print jobs are started at (almost) the same time and only then
     * waited on, so the two printers run concurrently instead of the label
     * sitting idle until the whole A4 job (rendering + spooling + the
     * physical print) has finished.
     */
    public function printOrder(Transaction $transaction)
    {
        if (!config('printing.enabled')) {
            return;
        }

        // 'queue' mode means this machine (e.g. shared hosting) has no
        // printers attached - there's nothing to shell out to here. The
        // local print agent (see PrintAgentRun / PrintAgentController)
        // discovers this transaction on its own by polling for paid orders
        // that don't have a successful print log yet, so no action is
        // needed on this side.
        if (config('printing.mode') === 'queue') {
            return;
        }

        $jobs = [
            $this->startPrintJob($transaction, 'invoice_a4', config('printing.invoice_printer'), $this->renderDocument($transaction, 'invoice_a4')),
            $this->startPrintJob($transaction, 'packing_label', config('printing.label_printer'), $this->renderDocument($transaction, 'packing_label')),
        ];

        foreach ($jobs as $job) {
            $this->finishPrintJob($job);
        }
    }

    /**
     * Renders one of the two auto-print documents for a transaction. Shared
     * by the local direct-print path above and by PrintAgentController,
     * which renders the same PDF on demand for the remote polling agent to
     * download and print.
     */
    public function renderDocument(Transaction $transaction, string $documentType)
    {
        if ($documentType === 'invoice_a4') {
            return $this->buildInvoicePdf($transaction);
        }

        if ($documentType === 'packing_label') {
            $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
            return $this->buildPackingSlipPdf($transaction, $details);
        }

        throw new \InvalidArgumentException("Unknown print document type: {$documentType}");
    }

    /**
     * Compact, paginated A4 invoice for auto-printing. Visually derived from
     * the system's existing invoice (same data: transaction, item lines,
     * delivery address) but with tighter spacing so a normal order fits on
     * one page, and a repeated header + "Page X/Y" footer for the rare order
     * that spans more than one page.
     */
    protected function buildInvoicePdf(Transaction $transaction)
    {
        $admin = Admin::first();
        $webSetting = WebsiteSetting::first();

        $details = TransactionDetail::select(
                'transaction_details.*', 'transaction_details.quantity as t_qty', 'u.uom_en', 'p.packages'
            )
            ->join('products AS p', 'p.id', 'transaction_details.product_id')
            ->leftJoin('setting_uoms AS u', 'u.id', 'p.product_type')
            ->where('transaction_id', $transaction->id)
            ->get();

        $delivery_state = State::find($transaction->state);
        $delivery_country = TblCountry::where('country_id', $transaction->country)->first();

        $pdf = \PDF::loadView('printing.invoice_a4', [
            'transaction' => $transaction,
            'details' => $details,
            'delivery_state' => $delivery_state,
            'delivery_country' => $delivery_country,
            'company_name' => $webSetting->invoice_name ?? $admin->website_name ?? config('app.name'),
            'company_address' => $webSetting->company_address ?? null,
            'company_phone' => $webSetting->company_phone ?? $admin->phone ?? null,
            'website_logo' => $this->resizedLogoPath($admin->website_logo),
            'payment_method_label' => $this->paymentMethodLabel($transaction),
        ]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * The company logo on disk is a 4500x4500px source image (meant for
     * print elsewhere in the app). Handing that straight to dompdf to embed
     * at a ~50pt display size was taking ~18s per document - dompdf/CPDF
     * scales it down itself but not efficiently. Cache a small resized copy
     * once and reuse it instead.
     */
    protected function resizedLogoPath(?string $relativeLogoPath): ?string
    {
        if (empty($relativeLogoPath)) {
            return null;
        }

        $sourcePath = public_path($relativeLogoPath);
        if (!file_exists($sourcePath)) {
            return null;
        }

        $cacheDir = storage_path('app/print_logo_cache');
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cachePath = $cacheDir . DIRECTORY_SEPARATOR . md5($sourcePath) . '.png';

        if (!file_exists($cachePath) || filemtime($cachePath) < filemtime($sourcePath)) {
            \Image::make($sourcePath)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($cachePath);
        }

        return $cachePath;
    }

    protected function paymentMethodLabel(Transaction $transaction)
    {
        if ($transaction->mall == 1) {
            return 'Cash Wallet';
        } elseif ($transaction->mall == 2) {
            return 'Topup Wallet';
        } elseif (!empty($transaction->bank_id)) {
            return 'Online Banking';
        } elseif (!empty($transaction->bank_slip)) {
            return 'Bank Transfer';
        } elseif (!empty($transaction->pv_purchase)) {
            return 'Point Wallet';
        } elseif (!empty($transaction->online_payment_method)) {
            return $transaction->online_payment_method;
        }

        return 'Online Payment';
    }

    protected function buildPackingSlipPdf(Transaction $transaction, $details)
    {
        $admin = Admin::first();
        $webSetting = WebsiteSetting::first();

        $deliveryState = State::find($transaction->state);
        $deliveryCountry = TblCountry::where('country_id', $transaction->country)->first();

        $pdf = \PDF::loadView('printing.packing_slip', [
            'transaction' => $transaction,
            'details' => $details,
            'company_name' => $webSetting->invoice_name ?? $admin->website_name ?? config('app.name'),
            'website_logo' => $this->resizedLogoPath($admin->website_logo),
            'delivery_state_name' => $deliveryState->name ?? null,
            'delivery_country_name' => $deliveryCountry->country_name ?? null,
        ]);

        // Real label measured by hand: 8cm x 6cm (not the printer's max-rated
        // "4x6 inch"). 1mm = 2.83465pt -> 80mm=226.77pt, 60mm=170.08pt.
        $pdf->setPaper([0, 0, 226.77, 170.08], 'portrait');

        return $pdf;
    }

    /**
     * Renders the PDF to a temp file and launches SumatraPDF without
     * waiting for it to finish (Process::start(), not run()). Returns the
     * bookkeeping needed to wait on and log the result later.
     */
    protected function startPrintJob(Transaction $transaction, string $documentType, string $printerName, $pdf)
    {
        $attempt = $this->nextAttemptNumber($transaction, $documentType);

        $job = [
            'transaction' => $transaction,
            'documentType' => $documentType,
            'printerName' => $printerName,
            'attempt' => $attempt,
        ];

        try {
            $tempDir = storage_path('app/print_jobs');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $filePath = $tempDir . DIRECTORY_SEPARATOR . $documentType . '_' . $transaction->transaction_no . '_' . time() . '.pdf';
            file_put_contents($filePath, $pdf->output());

            $job['process'] = $this->startSumatraProcess($filePath, $printerName, $documentType);
        } catch (\Throwable $e) {
            $job['error'] = $e->getMessage();
        }

        return $job;
    }

    /**
     * Waits for a job started by startPrintJob() and writes the log row.
     */
    protected function finishPrintJob(array $job)
    {
        ['transaction' => $transaction, 'documentType' => $documentType, 'printerName' => $printerName, 'attempt' => $attempt] = $job;

        if (isset($job['error'])) {
            $this->logPrintResult($transaction, $documentType, $printerName, $attempt, 'failed', $job['error']);
            return;
        }

        try {
            $process = $job['process'];
            $process->wait();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException("SumatraPDF exited with error code {$process->getExitCode()}");
            }

            $this->logPrintResult($transaction, $documentType, $printerName, $attempt, 'success', null);
        } catch (\Throwable $e) {
            Log::error("OrderPrintService: failed to print {$documentType} for {$transaction->transaction_no} on {$printerName}: " . $e->getMessage());
            $this->logPrintResult($transaction, $documentType, $printerName, $attempt, 'failed', $e->getMessage());
        }
    }

    /**
     * Public so PrintAgentController can log the result of a print job that
     * actually happened on the remote agent's machine, not this one.
     */
    public function logPrintResult(Transaction $transaction, string $documentType, string $printerName, int $attempt, string $status, ?string $message)
    {
        TransactionPrintLog::create([
            'transaction_id' => $transaction->id,
            'transaction_no' => $transaction->transaction_no,
            'document_type' => $documentType,
            'printer_name' => $printerName,
            'attempt' => $attempt,
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Public so both startPrintJob() (direct mode) and PrintAgentController
     * (queue mode) compute the same "which attempt number is this" and
     * "has this already succeeded / been retried too many times" logic.
     */
    public function nextAttemptNumber(Transaction $transaction, string $documentType): int
    {
        return TransactionPrintLog::where('transaction_id', $transaction->id)
            ->where('document_type', $documentType)
            ->count() + 1;
    }

    /**
     * True if this document still needs to be (re)printed: no successful
     * log yet, and we haven't exhausted the retry budget.
     */
    public function needsPrinting(Transaction $transaction, string $documentType): bool
    {
        $logs = TransactionPrintLog::where('transaction_id', $transaction->id)
            ->where('document_type', $documentType)
            ->get();

        if ($logs->contains('status', 'success')) {
            return false;
        }

        return $logs->count() < (int) config('printing.max_attempts', 5);
    }

    protected function startSumatraProcess(string $pdfFilePath, string $printerName, string $documentType): Process
    {
        // The A4 invoice is portrait-shaped; only the (landscape) label
        // needs 'landscape' - see LocalPdfPrinter for why this must match
        // the PDF's own shape.
        $orientation = ($documentType === 'packing_label') ? 'landscape' : 'portrait';

        return (new LocalPdfPrinter())->print($pdfFilePath, $printerName, $orientation);
    }
}
