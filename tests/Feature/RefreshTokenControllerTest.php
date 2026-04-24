<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a valid refresh token yields a new access token and revokes the old token.
     */
    public function test_refresh_token_success()
    {
        // Create user and generate a refresh token
        $user = User::factory()->create();
        $token = $user->createToken('refresh', ['*']);

        // Disable middleware that may interfere with API testing
        $this->withoutMiddleware();

        // Perform the refresh request
        $response = $this->postJson('/api/auth/refresh', [
            'refresh_token' => $token->plainTextToken,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in',
                 ]);

        // Old token should be removed from the database
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
            'revoked' => false,
        ]);

        // A new token record should now exist
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    /**
     * Test that an invalid refresh token returns 401.
     */
    public function test_refresh_token_invalid()
    {
        $user = User::factory()->create();

        $this->withoutMiddleware();

        $response = $this->postJson('/api/auth/refresh', [
            'refresh_token' => 'invalidtoken',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid or revoked refresh token.',
                 ]);
    }
}