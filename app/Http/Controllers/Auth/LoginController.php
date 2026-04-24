<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Controller responsible for handling user authentication.
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /**
     * Display the login form.
     *
     * @return Renderable
     */
    public function showLoginForm(): Renderable
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        // Validate the incoming request data.
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Log the login attempt (for audit).
        Log::info('Login attempt', [
            'email' => $credentials['email'],
            'timestamp' => now()->toDateTimeString(),
            'remote_ip' => $request->ip(),
        ]);

        // Attempt to authenticate the user.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate the session to prevent fixation attacks.
            $request->session()->regenerate();

            // Log successful authentication.
            Log::info('User authenticated successfully', [
                'user_id' => auth()->id(),
                'email' => $credentials['email'],
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Redirect to intended page or home.
            return redirect()->intended(route('home'));
        }

        // Authentication failed: log the event.
        Log::warning('Failed login attempt', [
            'email' => $credentials['email'],
            'timestamp' => now()->toDateTimeString(),
            'remote_ip' => $request->ip(),
        ]);

        // Throw a validation exception with a generic error message.
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}