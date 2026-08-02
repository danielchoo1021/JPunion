<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Every printer the local agent's `Get-Printer` has ever seen on the
 * machine, regardless of whether an admin has assigned it to a template
 * yet. Purely a cache to populate the "pick a printer" dropdown on
 * Setting Manage > Printer Manage, so admins select a real, exact printer
 * name instead of typing one by hand (typos there silently break printing).
 */
class DiscoveredPrinter extends Model
{
    protected $fillable = [
        'printer_name', 'port_name', 'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];
}
