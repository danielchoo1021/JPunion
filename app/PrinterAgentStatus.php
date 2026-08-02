<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrinterAgentStatus extends Model
{
    protected $fillable = [
        'document_type', 'printer_name', 'is_enabled', 'port_name', 'windows_status', 'is_ready', 'reported_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_ready' => 'boolean',
        'reported_at' => 'datetime',
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    /**
     * The agent is assumed dead (not just the printer) once its last
     * heartbeat is older than this - a stale "ready" row would otherwise
     * keep showing green in the admin page after the machine goes offline.
     * Kept well above print-agent.ps1's own $HeartbeatSeconds (60s) so a
     * single slow/missed check-in doesn't flicker this to "offline".
     */
    const STALE_AFTER_SECONDS = 150;

    public function isStale(): bool
    {
        if (empty($this->reported_at)) {
            return true;
        }

        return $this->reported_at->diffInSeconds(now()) > self::STALE_AFTER_SECONDS;
    }
}
