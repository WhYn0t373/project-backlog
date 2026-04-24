<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Handles user login and enforces email verification before allowing
 * a user to log in. Guests may still access the login form.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Guests can access all login routes except logout.
        $this->middleware('guest')->except('logout');

        // No need for 'verified' middleware on login routes.
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Override the authenticated hook to prevent unverified users from logging in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User           $user
     * @return \Illuminate\Http\RedirectResponse|null
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->hasVerifiedEmail()) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->with('status', 'verification-link-sent');
        }
    }
}