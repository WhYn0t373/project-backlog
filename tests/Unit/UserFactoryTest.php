<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function factory_creates_a_valid_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        $this->assertTrue(password_verify('password', $user->password));
    }

    /** @test */
    public function definition_returns_correct_attributes()
    {
        $factory = new \Database\Factories\UserFactory(app());

        $attributes = $factory->definition();

        $this->assertArrayHasKey('name', $attributes);
        $this->assertArrayHasKey('email', $attributes);
        $this->assertArrayHasKey('email_verified_at', $attributes);
        $this->assertArrayHasKey('password', $attributes);
        $this->assertArrayHasKey('role', $attributes);
        $this->assertArrayHasKey('remember_token', $attributes);

        $this->assertIsString($attributes['name']);
        $this->assertIsString($attributes['email']);
        $this->assertIsString($attributes['password']);
        $this->assertContains($attributes['role'], ['admin', 'user']);
    }
}