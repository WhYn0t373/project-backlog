<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FeaturePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    /** @test */
    public function it_allows_user_with_permission_to_view_features()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);
        $this->assertTrue(Gate::allows('viewAny', Feature::class));
    }

    /** @test */
    public function it_allows_user_with_permission_to_create_feature()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user);
        $this->assertTrue(Gate::allows('create', Feature::class));
    }

    /** @test */
    public function it_allows_user_with_permission_to_update_feature()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $feature = Feature::factory()->create();

        $this->actingAs($user);
        $this->assertTrue(Gate::allows('update', $feature));
    }

    /** @test */
    public function it_allows_user_with_permission_to_delete_feature()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $feature = Feature::factory()->create();

        $this->actingAs($user);
        $this->assertTrue(Gate::allows('delete', $feature));
    }

    /** @test */
    public function it_denies_actions_to_user_without_permission()
    {
        $user = User::factory()->create();
        // no role
        $feature = Feature::factory()->create();

        $this->actingAs($user);
        $this->assertFalse(Gate::allows('viewAny', Feature::class));
        $this->assertFalse(Gate::allows('create', Feature::class));
        $this->assertFalse(Gate::allows('update', $feature));
        $this->assertFalse(Gate::allows('delete', $feature));
    }
}