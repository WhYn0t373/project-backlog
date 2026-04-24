<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class EmailVerificationIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_verification_link_marks_user_as_verified()
    {
        Notification::fake();

        // Register a new user
        $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $user = User::where('email', 'jane@example.com')->firstOrFail();

        Notification::assertSentTo($user, VerifyEmail::class);

        // Retrieve the notification instance
        $notification = Notification::sent($user, VerifyEmail::class)->first();

        // Build the verification URL from the notification
        $verificationUrl = $notification->verificationUrl($user);

        // Simulate clicking the link
        $response = $this->get($verificationUrl);

        $response->assertRedirect('/login');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}