<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_jwt()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login_and_receive_jwt()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }

    public function test_access_protected_route_with_valid_token()
    {
        $user = User::factory()->create();

        $ttl = env('JWT_TTL', 60);
        $expiration = Carbon::now()->addMinutes($ttl)->timestamp;
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiration,
        ];
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $response->assertStatus(200)
                 ->assertJson([
                     'user' => [
                         'id' => $user->id,
                         'email' => $user->email,
                     ],
                 ]);
    }

    public function test_access_protected_route_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalidtoken',
        ])->getJson('/api/profile');

        $response->assertStatus(401);
    }
}