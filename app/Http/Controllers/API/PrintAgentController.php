<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
    protected $documentTypes = ['invoice_a4', 'packing_label'];

    public function queue(OrderPrintService $printService)
    {
        $transactions = Transaction::where('status', 1)
            ->orderBy('id')
            ->get();

        $jobs = [];

        foreach ($transactions as $transaction) {
            foreach ($this->documentTypes as $documentType) {
                if ($printService->needsPrinting($transaction, $documentType)) {
                    $jobs[] = [
                        'transaction_id' => $transaction->id,
                        'transaction_no' => $transaction->transaction_no,
                        'document_type' => $documentType,
                        'printer_name' => $documentType === 'invoice_a4'
                            ? config('printing.invoice_printer')
                            : config('printing.label_printer'),
                        'attempt' => $printService->nextAttemptNumber($transaction, $documentType),
                    ];
                }
            }
        }

        return response()->json(['jobs' => $jobs]);
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
