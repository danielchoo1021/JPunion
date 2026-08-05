<?php

namespace App\Http\Controllers\API;

use App\DiscoveredPrinter;
use App\Http\Controllers\Controller;
use App\PrinterAgentStatus;
use App\Services\OrderPrintService;
use App\Transaction;
use App\TransactionPrintLog;
use Illuminate\Http\Request;

/**
 * Endpoints polled by the local print agent (see PrintAgentRun / `php
 * artisan print:agent`) running on the machine that has the invoice and
 * label printers physically attached. Protected by the print.agent.token
 * middleware (a shared secret, not an admin login) since the agent is a
 * background process, not a browser session.
 *
 * queue() tells the agent what still needs printing; pdf() hands it the
 * document to print; ack() records what happened once it tried.
 */
class PrintAgentController extends Controller
{
    protected $documentTypes = ['invoice_a4', 'invoice_a5', 'packing_label', 'packing_label_roll'];

    public function queue(OrderPrintService $printService)
    {
        return response()->json(['jobs' => $this->pendingJobs($printService)]);
    }

    /**
     * Bulk-marks everything currently pending as printed, without actually
     * printing it - for onboarding a site that already has paid orders
     * before the print agent existed (or was ever pointed at it), so the
     * agent doesn't try to reprint a backlog of old orders. One request,
     * done entirely server-side, so it doesn't trip the api group's
     * throttle:60,1 the way looping ack() calls from the agent side would.
     */
    public function skipExisting(Request $request, OrderPrintService $printService)
    {
        $jobs = $this->pendingJobs($printService);

        foreach ($jobs as $job) {
            // Must log against the job's actual assigned printer_name, not
            // a placeholder - needsPrinting() now matches on the real
            // printer name (so multiple printers per template can be
            // tracked independently), so a mismatched name here silently
            // fails to suppress the job and it reappears in the queue.
            $printService->logPrintResult(
                Transaction::find($job['transaction_id']),
                $job['document_type'],
                $job['printer_name'],
                $job['attempt'],
                'skipped',
                'Skipped: marked as already printed during print-agent onboarding backfill.'
            );
        }

        return response()->json(['skipped' => count($jobs)]);
    }

    /**
     * One job per (transaction, enabled printer) - not per (transaction,
     * document_type). When two printers are assigned to the same template
     * (e.g. two packing-label printers), a paid order produces a job for
     * each, tracked and retried independently (see OrderPrintService::
     * needsPrinting()'s printer_name scoping), so a failure on one printer
     * never causes a reprint on a printer that already succeeded.
     */
    protected function pendingJobs(OrderPrintService $printService): array
    {
        $transactions = Transaction::where('status', 1)
            ->orderBy('id')
            ->get();

        $printers = PrinterAgentStatus::enabled()->get();

        $jobs = [];

        foreach ($transactions as $transaction) {
            foreach ($printers as $printer) {
                if ($printService->needsPrinting($transaction, $printer->document_type, $printer->printer_name)) {
                    $jobs[] = [
                        'transaction_id' => $transaction->id,
                        'transaction_no' => $transaction->transaction_no,
                        'document_type' => $printer->document_type,
                        'printer_name' => $printer->printer_name,
                        'attempt' => $printService->nextAttemptNumber($transaction, $printer->document_type, $printer->printer_name),
                    ];
                }
            }
        }

        return $jobs;
    }

    public function pdf(Request $request, $transactionId, string $documentType, OrderPrintService $printService)
    {
        if (!in_array($documentType, $this->documentTypes, true)) {
            abort(404);
        }

        $transaction = Transaction::findOrFail($transactionId);
        $pdf = $printService->renderDocument($transaction, $documentType);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $documentType . '_' . $transaction->transaction_no . '.pdf"',
        ]);
    }

    /**
     * The agent reports what Windows says about *every* printer it can see
     * on the machine (not just ones assigned to a template) - see
     * print-agent.ps1's Send-Heartbeat. Two things happen with that list:
     *
     * 1. Every printer is upserted into discovered_printers, regardless of
     *    whether it's assigned to anything - this is what powers the "pick
     *    a printer" dropdown on Setting Manage > Printer Manage, so admins
     *    select an exact, real printer name instead of typing one by hand.
     * 2. For printers that are already assigned to a template (a
     *    PrinterAgentStatus row exists for that printer_name), its live
     *    status fields get updated. Printers nobody has assigned yet are
     *    NOT auto-registered as a template's printer - that assignment is
     *    only ever made by an admin, via the dropdown above.
     */
    public function heartbeat(Request $request)
    {
        $request->validate([
            'printers' => 'required|array',
            'printers.*.printer_name' => 'required|string',
            'printers.*.port_name' => 'nullable|string',
            'printers.*.windows_status' => 'nullable|string',
            'printers.*.is_ready' => 'required|boolean',
        ]);

        foreach ($request->input('printers') as $printer) {
            DiscoveredPrinter::updateOrCreate(
                ['printer_name' => $printer['printer_name']],
                [
                    'port_name' => $printer['port_name'] ?? null,
                    'last_seen_at' => now(),
                ]
            );

            PrinterAgentStatus::where('printer_name', $printer['printer_name'])->update([
                'port_name' => $printer['port_name'] ?? null,
                'windows_status' => $printer['windows_status'] ?? null,
                'is_ready' => $printer['is_ready'],
                'reported_at' => now(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function ack(Request $request, $transactionId, string $documentType, OrderPrintService $printService)
    {
        if (!in_array($documentType, $this->documentTypes, true)) {
            abort(404);
        }

        $request->validate([
            'status' => 'required|in:success,failed',
            'printer_name' => 'required|string',
            'attempt' => 'required|integer|min:1',
            'message' => 'nullable|string',
        ]);

        $transaction = Transaction::findOrFail($transactionId);

        $printService->logPrintResult(
            $transaction,
            $documentType,
            $request->input('printer_name'),
            (int) $request->input('attempt'),
            $request->input('status'),
            $request->input('message')
        );

        return response()->json(['ok' => true]);
    }
}
