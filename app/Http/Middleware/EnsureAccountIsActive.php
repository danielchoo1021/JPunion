<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAccountIsActive
{
    /**
     * Guards that should be scanned for inactive users.
     *
     * @var array<int, string>
     */
    protected $guards = ['web', 'admin', 'merchant', 'agent', 'staff', 'corporate'];

    /**
     * Handle an incoming request.
     *
     * If a logged-in account is no longer active, immediately log it out and
     * redirect the user back to the appropriate login page with an error.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        foreach ($this->guards as $guard) {
            $user = Auth::guard($guard)->user();

            if ($user && (string) $user->status !== '1') {
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route($this->resolveRedirectRoute($guard))
                    ->withErrors([
                        'inactive' => __('Your account is inactive. Please contact support for assistance.'),
                    ]);
            }
        }

        return $next($request);
    }

    /**
     * Resolve the redirect route based on the guard that was logged out.
     *
     * @param  string  $guard
     * @return string
     */
    protected function resolveRedirectRoute(string $guard): string
    {
        return match ($guard) {
            'admin' => 'admin_login',
            'merchant' => 'merchant_login',
            default => 'login',
        };
    }
}


