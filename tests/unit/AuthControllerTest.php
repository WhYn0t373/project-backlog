<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuthControllerTest extends TestCase
{
    public function test_generate_token_returns_valid_jwt()
    {
        $user = User::factory()->create();

        $controller = new AuthController();

        $token = $controller->generateToken($user);

        $this->assertIsString($token);

        $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

        $this->assertEquals($user->id, $decoded->sub);
        $this->assertEquals(config('app.url'), $decoded->iss);
        $this->assertObjectHasAttribute('iat', $decoded);
        $this->assertObjectHasAttribute('exp', $decoded);
    }

    public function test_register_successfully_creates_user_and_returns_token()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'unique@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', ['email' => 'unique@example.com']);
    }

    public function test_register_fails_on_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $payload = [
            'name' => 'Test User',
            'email' => 'duplicate@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422); // validation error
    }
}