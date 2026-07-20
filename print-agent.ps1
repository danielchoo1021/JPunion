# Pure PowerShell print agent - no PHP/XAMPP required on this machine.
# Polls the hosted site for paid orders awaiting print, downloads the PDF,
# and sends it to the local printer via SumatraPDF. Equivalent to
# `php artisan print:agent` (app/Console/Commands/PrintAgentRun.php) but
# with zero extra installs beyond SumatraPDF.exe itself (which is portable,
# no install needed either).
#
# Only these two files are needed on the printer machine:
#   print-agent.ps1        (this file)
#   SumatraPDF-3.6.1-64.exe (or wherever $SumatraPath below points)
#
# ============================== CONFIG ======================================
$Token              = "o7JEzI8lOmuSAZqYMQVXRWUbLBN30dhigPC5cTenjsDa9yxw"
$RemoteUrl          = "https://slateblue-bear-247969.hostingersite.com"
$SumatraPath        = "C:\xampp\htdocs\DemoQC\demoqc\tools\SumatraPDF\SumatraPDF-3.6.1-64.exe"
$InvoicePrinterName = "Canon LBP6030/6040/6018L"
$LabelPrinterName   = "D520 Printer"
$PollSeconds        = 5
$TempDir            = "$PSScriptRoot\print_jobs"
# ==============================================================================

$Headers = @{ "X-Print-Agent-Token" = $Token }

if (-not (Test-Path $SumatraPath)) {
    Write-Error "SumatraPDF not found at $SumatraPath - fix `$SumatraPath at the top of this script."
    exit 1
}
if (-not (Test-Path $TempDir)) {
    New-Item -ItemType Directory -Path $TempDir -Force | Out-Null
}

function Print-Job {
    param($Job)

    $documentType = $Job.document_type
    $transactionId = $Job.transaction_id
    $transactionNo = $Job.transaction_no
    $attempt = $Job.attempt

    $printerName = if ($documentType -eq "invoice_a4") { $InvoicePrinterName } else { $LabelPrinterName }
    $orientation = if ($documentType -eq "packing_label") { "landscape" } else { "portrait" }

    Write-Output "Printing $documentType for $transactionNo (attempt $attempt) on `"$printerName`"..."

    try {
        $pdfUrl = "$RemoteUrl/api/print-agent/pdf/$transactionId/$documentType"
        $filePath = Join-Path $TempDir "$($documentType)_$($transactionNo)_$([DateTimeOffset]::Now.ToUnixTimeSeconds()).pdf"

        Invoke-WebRequest -Uri $pdfUrl -Headers $Headers -OutFile $filePath -TimeoutSec 30 -ErrorAction Stop | Out-Null

        # -print-settings must match the PDF's own shape or Sumatra rotates
        # the content to "fit" a mismatched orientation (this bit the same
        # print-to-SumatraPDF invocation in the PHP agent when it was
        # missing - keep noscale + matching orientation here too).
        #
        # -Wait combined with -WindowStyle Hidden is a known PowerShell/.NET
        # quirk that can hang indefinitely even after the child process has
        # actually exited. Use -NoNewWindow (skips ShellExecute, tracks the
        # real process handle) instead, and enforce our own timeout via
        # WaitForExit() rather than trusting -Wait to return.
        $proc = Start-Process -FilePath $SumatraPath `
            -ArgumentList @("-print-to", "`"$printerName`"", "-print-settings", "noscale,$orientation", "-silent", "`"$filePath`"") `
            -PassThru -NoNewWindow

        $exited = $proc.WaitForExit(60000)
        if (-not $exited) {
            try { $proc.Kill() } catch {}
            throw "SumatraPDF did not exit within 60s - killed it."
        }

        if ($proc.ExitCode -ne 0) {
            throw "SumatraPDF exited with code $($proc.ExitCode)"
        }

        Send-Ack -TransactionId $transactionId -DocumentType $documentType -PrinterName $printerName -Attempt $attempt -Status "success" -Message $null
    } catch {
        Write-Output "Failed: $($_.Exception.Message)"
        Send-Ack -TransactionId $transactionId -DocumentType $documentType -PrinterName $printerName -Attempt $attempt -Status "failed" -Message $_.Exception.Message
    }
}

function Send-Ack {
    param($TransactionId, $DocumentType, $PrinterName, $Attempt, $Status, $Message)

    $body = @{
        status       = $Status
        printer_name = $PrinterName
        attempt      = $Attempt
        message      = $Message
    } | ConvertTo-Json

    try {
        Invoke-RestMethod -Uri "$RemoteUrl/api/print-agent/ack/$TransactionId/$DocumentType" `
            -Headers $Headers -Method Post -Body $body -ContentType "application/json" -TimeoutSec 30 -ErrorAction Stop | Out-Null
    } catch {
        Write-Output "Ack failed for $DocumentType/$TransactionId : $($_.Exception.Message)"
    }
}

Write-Output "Polling $RemoteUrl every ${PollSeconds}s. Ctrl+C to stop."

while ($true) {
    try {
        $resp = Invoke-RestMethod -Uri "$RemoteUrl/api/print-agent/queue" -Headers $Headers -Method Get -TimeoutSec 30 -ErrorAction Stop
        foreach ($job in $resp.jobs) {
            Print-Job -Job $job
        }
    } catch {
        Write-Output "Queue request failed: $($_.Exception.Message)"
    }

    Start-Sleep -Seconds $PollSeconds
}
