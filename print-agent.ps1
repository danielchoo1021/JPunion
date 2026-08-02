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
$PollSeconds        = 5
$HeartbeatSeconds   = 60
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

    # Which printer to use comes from the server (Setting Manage > Printer
    # Manage assigns printers to templates) - the agent doesn't decide this
    # locally anymore, so adding/reassigning a printer never requires
    # touching this script.
    $printerName = $Job.printer_name
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

        # Touching .Handle right after Start() forces .NET to cache the
        # process handle internally - without this, WaitForExit()/ExitCode
        # can misreport on a Process object obtained via -PassThru, even
        # when the process actually ran and exited fine. This was causing
        # every real, successful print to be logged (and retried) as a
        # failure.
        $proc.Handle | Out-Null

        $exited = $proc.WaitForExit(60000)
        if (-not $exited) {
            try { $proc.Kill() } catch {}
            throw "SumatraPDF did not exit within 60s - killed it."
        }

        $proc.Refresh()
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

function Send-Heartbeat {
    # Report every printer Windows knows about on this machine - not just
    # ones already assigned to a template. The server uses the full list to
    # populate the "pick a printer" dropdown on Setting Manage > Printer
    # Manage (so assigning a new printer there is a dropdown pick, not
    # typing an exact name by hand), and separately updates live status for
    # whichever of these are actually assigned.
    try {
        $localPrinters = Get-Printer -ErrorAction Stop
    } catch {
        Write-Output "Heartbeat skipped: could not list local printers ($($_.Exception.Message))"
        return
    }

    $printers = $localPrinters | ForEach-Object {
        $status = $_.PrinterStatus.ToString()
        @{
            printer_name   = $_.Name
            port_name      = $_.PortName
            windows_status = $status
            is_ready       = ($status -eq "Normal")
        }
    }

    $body = @{ printers = @($printers) } | ConvertTo-Json -Depth 4

    try {
        Invoke-RestMethod -Uri "$RemoteUrl/api/print-agent/heartbeat" `
            -Headers $Headers -Method Post -Body $body -ContentType "application/json" -TimeoutSec 15 -ErrorAction Stop | Out-Null
    } catch {
        Write-Output "Heartbeat failed: $($_.Exception.Message)"
    }
}

Write-Output "Polling $RemoteUrl every ${PollSeconds}s. Ctrl+C to stop."

$lastHeartbeat = [DateTime]::MinValue

while ($true) {
    try {
        $resp = Invoke-RestMethod -Uri "$RemoteUrl/api/print-agent/queue" -Headers $Headers -Method Get -TimeoutSec 30 -ErrorAction Stop
        foreach ($job in $resp.jobs) {
            Print-Job -Job $job
        }
    } catch {
        Write-Output "Queue request failed: $($_.Exception.Message)"
    }

    if (([DateTime]::Now - $lastHeartbeat).TotalSeconds -ge $HeartbeatSeconds) {
        Send-Heartbeat
        $lastHeartbeat = [DateTime]::Now
    }

    Start-Sleep -Seconds $PollSeconds
}
