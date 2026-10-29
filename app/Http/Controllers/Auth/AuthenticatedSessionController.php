<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        switch ($user->role) {
            case 'ptj':
                return redirect()->route('ptj.dashboard');

            case 'kj':
                return redirect()->route('kj.dashboard');

            case 'bendahari':
                return redirect()->route('bendahari.dashboard');

            case 'admin':
                return redirect()->route('admin.dashboard');

            default:
                Auth::logout();

                return redirect()->route('login')
                    ->withErrors([
                        'staff_id' => 'Your account role is invalid.',
                    ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}