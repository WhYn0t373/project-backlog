<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Handles user registration and immediately sends an email verification
 * notification after the user has been created.
 */
class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the request payload.
        $this->validator($request->all())->validate();

        // Create the user.
        $user = $this->create($request->all());

        // Fire the Registered event (useful for third‑party packages).
        event(new Registered($user));

        // Send verification email immediately.
        $user->sendEmailVerificationNotification();

        // Redirect to login with a status message indicating a verification link has been sent.
        return redirect()
            ->route('login')
            ->with('status', 'verification-link-sent');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array<string,mixed>  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array<string,mixed>  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}