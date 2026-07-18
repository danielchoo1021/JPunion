<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Auto-print on payment success
    |--------------------------------------------------------------------------
    |
    | Set to false to disable automatic printing entirely (e.g. on a machine
    | with no printers attached) without touching the Observer/Service code.
    |
    */
    'enabled' => env('AUTO_PRINT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Print mode
    |--------------------------------------------------------------------------
    |
    | 'direct' - this machine has the printers attached; printOrder() shells
    |            out to the local SumatraPDF straight away (the original
    |            behaviour, correct on the local XAMPP/Windows box).
    | 'queue'  - this machine (e.g. shared hosting) has no printers attached.
    |            printOrder() does nothing; instead a local agent running on
    |            the machine with the printers polls the /api/print-agent/*
    |            endpoints below and prints from there. See PrintAgentRun.
    |
    */
    'mode' => env('AUTO_PRINT_MODE', 'direct'),

    /*
    |--------------------------------------------------------------------------
    | Printer names
    |--------------------------------------------------------------------------
    |
    | These must match the printer names exactly as registered in Windows
    | ("Devices and Printers" / `Get-Printer` in PowerShell).
    |
    */
    'invoice_printer' => env('PRINTER_A4_NAME', 'Canon LBP6030/6040/6018L'),
    'label_printer' => env('PRINTER_LABEL_NAME', 'D520 Printer'),

    /*
    |--------------------------------------------------------------------------
    | Silent PDF printing tool
    |--------------------------------------------------------------------------
    |
    | Path to the portable SumatraPDF executable used to send a PDF straight
    | to a named Windows printer without opening any dialog.
    |
    */
    'sumatra_path' => env('SUMATRA_PDF_PATH', base_path('tools/SumatraPDF/SumatraPDF-3.6.1-64.exe')),

    /*
    |--------------------------------------------------------------------------
    | Print agent (queue mode)
    |--------------------------------------------------------------------------
    |
    | agent_token  - shared secret the /api/print-agent/* endpoints require
    |                (header X-Print-Agent-Token). Must match on both the
    |                hosted site's .env and the local agent's .env.
    | remote_url   - only used by the local agent (print:agent command) to
    |                know which site to poll. Not used in 'direct' mode.
    | poll_seconds - how often the local agent polls the queue endpoint.
    | max_attempts - stop retrying a document after this many failed prints
    |                (still shown in transaction_print_logs for review).
    |
    */
    'agent_token' => env('PRINT_AGENT_TOKEN'),
    'remote_url' => env('PRINT_AGENT_REMOTE_URL'),
    'poll_seconds' => env('PRINT_AGENT_POLL_SECONDS', 5),
    'max_attempts' => env('PRINT_AGENT_MAX_ATTEMPTS', 5),

];
