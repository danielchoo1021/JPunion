<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Guards tried in order for this shared login form. Merchant and Admin
     * have their own dedicated login pages (/merchant_login, /admin_login),
     * but Agent never got one, so it shares this Customer-facing /login
     * page instead of being unable to log in at all.
     *
     * @var array<int, string>
     */
    protected $loginGuards = ['web', 'agent'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Ensure only active accounts can attempt to authenticate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['status' => '1']
        );
    }

    /**
     * Try each guard in $loginGuards in turn until one accepts the
     * credentials, instead of only ever checking the default ('web') guard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        foreach ($this->loginGuards as $guardName) {
            if (Auth::guard($guardName)->attempt($credentials, $request->boolean('remember'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whichever of $loginGuards currently has an authenticated session -
     * used both right after attemptLogin() succeeds, and later on logout
     * (a separate request, where only the session state tells us which
     * guard is active).
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        foreach ($this->loginGuards as $guardName) {
            if (Auth::guard($guardName)->check()) {
                return Auth::guard($guardName);
            }
        }

        return Auth::guard('web');
    }

    /**
     * Immediately logout users whose status changed between login attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Http\RedirectResponse|void
     */
    protected function authenticated(Request $request, $user)
    {
        if ((string) $user->status === '1') {
            return;
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'inactive' => __('Your account is inactive. Please contact support for assistance.'),
        ]);
    }
}
