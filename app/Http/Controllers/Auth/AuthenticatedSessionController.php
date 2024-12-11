<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    public function showLogin()
    {
        return view('auth.login');
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy()
    {
        auth()->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return redirect('/signin');
    }
}
