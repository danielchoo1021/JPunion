<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Guards the /api/print-agent/* endpoints used by the local print agent
 * (PrintAgentRun) polling from the machine that has the printers attached.
 * This is machine-to-machine, not a logged-in admin session, so it checks a
 * shared secret header instead of any auth guard.
 */
class VerifyPrintAgentToken
{
    public function handle(Request $request, Closure $next)
    {
        $expected = config('printing.agent_token');

        if (empty($expected) || !hash_equals((string) $expected, (string) $request->header('X-Print-Agent-Token'))) {
            abort(403, 'Invalid print agent token');
        }

        return $next($request);
    }
}
