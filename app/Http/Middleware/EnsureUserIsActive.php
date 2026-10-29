<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * If an authenticated user's account has been deactivated
     * by the System Administrator, terminate the existing session
     * and return the user to the login page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Check Current Authenticated User
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check()
            &&
            ! Auth::user()->is_active
        ) {

            /*
            |--------------------------------------------------------------------------
            | Log Out Deactivated User
            |--------------------------------------------------------------------------
            */

            Auth::logout();


            /*
            |--------------------------------------------------------------------------
            | Destroy Existing Session
            |--------------------------------------------------------------------------
            |
            | This prevents the deactivated account from continuing to use
            | an already-authenticated browser session.
            |
            */

            $request->session()->invalidate();


            /*
            |--------------------------------------------------------------------------
            | Generate New CSRF Token
            |--------------------------------------------------------------------------
            */

            $request->session()->regenerateToken();


            /*
            |--------------------------------------------------------------------------
            | Redirect to Login
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('login')
                ->withErrors([
                    'staff_id' =>
                        'Your account has been deactivated. Please contact the System Administrator.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Active User
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}