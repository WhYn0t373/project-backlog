<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

/**
 * PostApiTest
 *
 * Tests for CRUD operations on posts with proper authentication and authorization.
 */
class PostApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin user can perform all CRUD operations.
     *
     * @return void
     */
    public function test_admin_can_crud_posts()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create
        $payload = [
            'title' => 'Test Post',
            'body'  => 'This is a test body.',
        ];

        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/posts', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Post']);

        $postId = $response->json('id');

        // Read
        $response = $this->actingAs($admin, 'sanctum')->getJson("/api/posts/{$postId}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Test Post']);

        // Update
        $updatePayload = [
            'title' => 'Updated Title',
        ];

        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/posts/{$postId}", $updatePayload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated Title']);

        // Delete
        $response = $this->actingAs($admin, 'sanctum')->deleteJson("/api/posts/{$postId}");
        $response->assertStatus(204);

        // Verify deletion
        $this->assertDatabaseMissing('posts', ['id' => $postId]);
    }

    /**
     * Test that a non-admin user is forbidden from creating, updating, or deleting posts.
     *
     * @return void
     */
    public function test_non_admin_cannot_modify_posts()
    {
        $user = User::factory()->create(['role' => 'user']);
        $post = Post::factory()->create();

        // Attempt to create
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/posts', [
            'title' => 'Forbidden Post',
            'body'  => 'Should not be allowed.',
        ]);
        $response->assertStatus(403);

        // Attempt to update
        $response = $this->actingAs($user, 'sanctum')->putJson("/api/posts/{$post->id}", [
            'title' => 'Hacked Title',
        ]);
        $response->assertStatus(403);

        // Attempt to delete
        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(403);
    }

    /**
     * Test that unauthenticated requests receive a 401 status.
     *
     * @return void
     */
    public function test_unauthenticated_requests_are_unauthorized()
    {
        // Unauthenticated GET
        $this->getJson('/api/posts')->assertStatus(401);

        // Unauthenticated POST
        $this->postJson('/api/posts', [
            'title' => 'No Auth',
            'body'  => 'Should fail.',
        ])->assertStatus(401);
    }

    /**
     * Test validation errors for create and update actions.
     *
     * @return void
     */
    public function test_validation_errors()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Missing title on create
        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/posts', [
            'body' => 'Missing title.',
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);

        // Missing body on update
        $post = Post::factory()->create();
        $response = $this->actingAs($admin, 'sanctum')->putJson("/api/posts/{$post->id}", [
            'title' => 'Title only',
            // body omitted intentionally
        ]);
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['body']);
    }
}