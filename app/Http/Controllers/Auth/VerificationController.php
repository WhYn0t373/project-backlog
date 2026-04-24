<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Custom verification controller (optional).  The default Laravel
 * controller handles signed URL verification, but this class demonstrates
 * a manual approach that could be used if the project prefers a bespoke
 * implementation.
 */
class VerificationController extends Controller
{
    /**
     * Verify the authenticated user's email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = Auth::user();

        // No user is logged in, so we cannot verify.
        if (! $user) {
            abort(403, 'Unauthorized action.');
        }

        // If the email is already verified, redirect back with a status.
        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('login')
                ->with('status', 'already-verified');
        }

        // Mark the email as verified.
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            return redirect()
                ->route('login')
                ->with('status', 'verified');
        }

        // If something went wrong, redirect back with an error.
        return redirect()
            ->route('login')
            ->with('status', 'verification-failed');
    }
}