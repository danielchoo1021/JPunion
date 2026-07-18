<?php

namespace App\Services;

use Symfony\Component\Process\Process;

/**
 * Launches the portable SumatraPDF executable to silently print a PDF file
 * already sitting on disk to a named local Windows printer. Shared by
 * OrderPrintService (prints straight away, on the machine that made the
 * transaction) and PrintAgentRun (the local agent that polls a remote site
 * for jobs and prints them here) so the printer-invocation quirks below are
 * fixed in exactly one place.
 */
class LocalPdfPrinter
{
    /**
     * @param  string  $orientation  'portrait' or 'landscape' - must match
     *   the PDF's own shape, not the printer's current form, or Sumatra
     *   will rotate the content to "fit" a mismatched orientation.
     */
    public function print(string $pdfFilePath, string $printerName, string $orientation = 'portrait'): Process
    {
        $sumatra = config('printing.sumatra_path');

        if (!file_exists($sumatra)) {
            throw new \RuntimeException("SumatraPDF executable not found at {$sumatra}");
        }

        $process = new Process([$sumatra, '-print-to', $printerName, '-print-settings', "noscale,{$orientation}", '-silent', $pdfFilePath]);
        $process->setTimeout(60);

        // Symfony Process normally captures stdout/stderr through pipes.
        // SumatraPDF writes a stream of "ParseFlags" debug lines to stdout
        // while it prints; piping that (instead of letting it go to a real
        // console, as happens when run directly from a shell) was corrupting
        // the print job partway through - the label would come out upside
        // down and cut off mid-content. Disabling output capture avoids the
        // pipe entirely and made the same job print correctly.
        $process->disableOutput();
        $process->start();

        return $process;
    }
}
