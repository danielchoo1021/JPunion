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

];
