<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Tests for CRUD operations on the Feature resource.
 */
class FeatureCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed permissions and roles
        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    /**
     * Test that an admin can create a feature.
     */
    public function test_admin_can_create_feature(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $payload = [
            'name' => 'New Feature',
            'description' => 'Description of new feature',
        ];

        $response = $this->actingAs($admin)->postJson(route('features.store'), $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'New Feature']);

        $this->assertDatabaseHas('features', ['name' => 'New Feature']);
    }

    /**
     * Test that a non-admin user cannot create a feature.
     */
    public function test_non_admin_cannot_create_feature(): void
    {
        $user = User::factory()->create();
        // No role assigned

        $payload = [
            'name' => 'Unauthorized Feature',
            'description' => 'Should not be created',
        ];

        $response = $this->actingAs($user)->postJson(route('features.store'), $payload);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('features', ['name' => 'Unauthorized Feature']);
    }

    /**
     * Test that an admin can view features list.
     */
    public function test_admin_can_view_features(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Feature::factory()->count(3)->create();

        $response = $this->actingAs($admin)->getJson(route('features.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data',
                     'links',
                     'meta',
                 ]);
    }

    /**
     * Test that an admin can update a feature.
     */
    public function test_admin_can_update_feature(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $feature = Feature::factory()->create(['name' => 'Old Name', 'description' => 'Old description']);

        $payload = ['name' => 'Updated Name', 'description' => 'Updated description'];

        $response = $this->actingAs($admin)->putJson(route('features.update', $feature), $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('features', ['id' => $feature->id, 'name' => 'Updated Name']);
    }

    /**
     * Test that an admin can delete a feature.
     */
    public function test_admin_can_delete_feature(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $feature = Feature::factory()->create();

        $response = $this->actingAs($admin)->deleteJson(route('features.destroy', $feature));

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Feature deleted successfully.']);

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    /**
     * Test that a non-logged-in user receives a redirect to login.
     */
    public function test_guest_cannot_access_feature_routes(): void
    {
        $response = $this->getJson(route('features.index'));
        $response->assertRedirect(route('login'));

        $response = $this->deleteJson(route('features.destroy', Feature::factory()->create()));
        $response->assertRedirect(route('login'));
    }
}