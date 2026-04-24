<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class RegisterIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_and_receives_verification_email()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status', 'verification-link-sent');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        Mail::assertQueued(VerifyEmail::class);
    }
}