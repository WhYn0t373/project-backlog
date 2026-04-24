<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function fillable_attributes_are_mass_assignable()
    {
        $attributes = [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'secret123',
        ];

        $user = User::create($attributes);

        $this->assertDatabaseHas('users', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
        ]);

        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    /** @test */
    public function hidden_attributes_are_not_present_in_serialized_form()
    {
        $user = User::factory()->make();

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    /** @test */
    public function email_verified_at_is_cast_to_carbon_instance()
    {
        $now = Carbon::now();
        $user = User::factory()->make(['email_verified_at' => $now]);

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
        $this->assertTrue($user->email_verified_at->eq($now));
    }
}