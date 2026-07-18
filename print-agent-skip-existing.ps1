# One-time cleanup: marks every order currently sitting in the print queue
# as already-printed (without actually printing it), so historical/test
# orders don't get printed the first time the agent turns on. Run this once,
# with print:agent NOT running at the same time to avoid racing it.
#
# Usage: edit $token/$remoteUrl below to match your .env, then run:
#   C:\xampp\php\php.exe -r "" ; powershell -File print-agent-skip-existing.ps1
# or just: right-click > Run with PowerShell

$token = "o7JEzI8lOmuSAZqYMQVXRWUbLBN30dhigPC5cTenjsDa9yxw"
$remoteUrl = "https://slateblue-bear-247969.hostingersite.com"

$headers = @{ "X-Print-Agent-Token" = $token }

$resp = Invoke-RestMethod -Uri "$remoteUrl/api/print-agent/queue" -Headers $headers -Method Get
$jobs = $resp.jobs

if (-not $jobs -or $jobs.Count -eq 0) {
    Write-Output "Queue is already empty, nothing to skip."
    exit
}

Write-Output "Found $($jobs.Count) pending print jobs. Marking all as skipped (already printed)..."

# The site's /api/* routes are throttled to 60 requests/minute (Kernel.php
# 'api' group). Space requests out so a large backlog doesn't trip that
# limit; on a 429 back off and retry the same job instead of losing it.
foreach ($job in $jobs) {
    $body = @{
        status       = "success"
        printer_name = "backfill-skip"
        attempt      = $job.attempt
        message      = "Skipped: marked as already printed during print-agent onboarding backfill."
    } | ConvertTo-Json

    $ackUrl = "$remoteUrl/api/print-agent/ack/$($job.transaction_id)/$($job.document_type)"

    $done = $false
    while (-not $done) {
        try {
            # -ErrorAction Stop is required here: Invoke-RestMethod raises a
            # non-terminating error on HTTP failures by default, which a
            # bare try/catch does NOT intercept - without this, a 429 would
            # silently fall through to the "success" line below instead of
            # being retried.
            Invoke-RestMethod -Uri $ackUrl -Headers $headers -Method Post -Body $body -ContentType "application/json" -ErrorAction Stop | Out-Null
            Write-Output "Skipped $($job.document_type) for $($job.transaction_no)"
            $done = $true
        } catch {
            if ($_.Exception.Response -and $_.Exception.Response.StatusCode.value__ -eq 429) {
                Write-Output "Rate limited, waiting 15s before retrying $($job.document_type) for $($job.transaction_no)..."
                Start-Sleep -Seconds 15
            } else {
                Write-Output "Failed $($job.document_type) for $($job.transaction_no): $($_.Exception.Message)"
                $done = $true
            }
        }
    }

    Start-Sleep -Milliseconds 1100
}

Write-Output "Done. Re-run print:agent - the queue should now be empty until a real new order comes in."
