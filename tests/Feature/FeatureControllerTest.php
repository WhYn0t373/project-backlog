<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class FeatureControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    /** @test */
    public function admin_can_create_feature_via_api()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $payload = ['name' => 'API Feature', 'description' => 'API desc'];

        $response = $this->actingAs($admin)->postJson(route('features.store'), $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'API Feature']);

        $this->assertDatabaseHas('features', ['name' => 'API Feature']);
    }

    /** @test */
    public function non_admin_cannot_create_feature()
    {
        $user = User::factory()->create();

        $payload = ['name' => 'Bad Feature'];

        $response = $this->actingAs($user)->postJson(route('features.store'), $payload);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('features', ['name' => 'Bad Feature']);
    }

    /** @test */
    public function admin_can_view_features_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Feature::factory()->count(2)->create();

        $response = $this->actingAs($admin)->getJson(route('features.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'links', 'meta']);
    }

    /** @test */
    public function admin_can_show_feature()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $feature = Feature::factory()->create();

        $response = $this->actingAs($admin)->getJson(route('features.show', $feature));

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $feature->id]);
    }

    /** @test */
    public function admin_can_update_feature()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $feature = Feature::factory()->create(['name' => 'Old']);

        $payload = ['name' => 'New', 'description' => 'New desc'];

        $response = $this->actingAs($admin)->putJson(route('features.update', $feature), $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'New']);

        $this->assertDatabaseHas('features', ['id' => $feature->id, 'name' => 'New']);
    }

    /** @test */
    public function admin_can_delete_feature()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $feature = Feature::factory()->create();

        $response = $this->actingAs($admin)->deleteJson(route('features.destroy', $feature));

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Feature deleted successfully.']);

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    /** @test */
    public function guest_redirected_to_login_on_feature_routes()
    {
        $response = $this->getJson(route('features.index'));
        $response->assertRedirect(route('login'));

        $response = $this->postJson(route('features.store'), ['name' => 'Guest']);
        $response->assertRedirect(route('login'));
    }
}