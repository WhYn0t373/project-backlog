<?php

namespace Tests\Feature;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the features table exists with the correct columns.
     *
     * @return void
     */
    public function test_features_table_schema()
    {
        $this->assertTrue(
            DB::connection()->getSchemaBuilder()->hasTable('features'),
            'The features table does not exist.'
        );

        $columns = Schema::getColumnListing('features');

        $this->assertContains('id', $columns);
        $this->assertContains('name', $columns);
        $this->assertContains('description', $columns);
        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    /**
     * Test that a feature can be created via the API and returned correctly.
     *
     * @return void
     */
    public function test_store_feature()
    {
        $payload = [
            'name' => 'Test Feature',
            'description' => 'A description for the test feature.',
        ];

        $response = $this->postJson('/api/features', $payload);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'created_at', 'updated_at']]);

        $this->assertDatabaseHas('features', ['name' => 'Test Feature']);
    }

    /**
     * Test that the index endpoint returns all features.
     *
     * @return void
     */
    public function test_index_features()
    {
        Feature::factory()->count(3)->create();

        $response = $this->getJson('/api/features');

        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [['id', 'name', 'description', 'created_at', 'updated_at'] ]]);
    }

    /**
     * Test retrieving a single feature.
     *
     * @return void
     */
    public function test_show_feature()
    {
        $feature = Feature::factory()->create();

        $response = $this->getJson("/api/features/{$feature->id}");

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'created_at', 'updated_at']])
            ->assertJsonFragment(['id' => $feature->id, 'name' => $feature->name]);
    }

    /**
     * Test updating a feature.
     *
     * @return void
     */
    public function test_update_feature()
    {
        $feature = Feature::factory()->create(['name' => 'Old Name']);

        $payload = ['name' => 'New Name', 'description' => 'Updated description'];

        $response = $this->putJson("/api/features/{$feature->id}", $payload);

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name', 'description' => 'Updated description']);

        $this->assertDatabaseHas('features', ['id' => $feature->id, 'name' => 'New Name']);
    }

    /**
     * Test deleting a feature.
     *
     * @return void
     */
    public function test_destroy_feature()
    {
        $feature = Feature::factory()->create();

        $response = $this->deleteJson("/api/features/{$feature->id}");

        $response
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Feature deleted successfully.']);

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    /**
     * Test that validation fails when name is missing.
     *
     * @return void
     */
    public function test_store_feature_validation_failure()
    {
        $payload = [
            'description' => 'Missing name',
        ];

        $response = $this->postJson('/api/features', $payload);

        $response->assertStatus(422) // Laravel returns 422 on validation errors
                 ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test that duplicate feature names produce a 500 error (database unique constraint violation).
     *
     * @return void
     */
    public function test_store_duplicate_feature_name()
    {
        Feature::factory()->create(['name' => 'Unique Feature']);

        $payload = [
            'name' => 'Unique Feature',
            'description' => 'Duplicate name',
        ];

        $response = $this->postJson('/api/features', $payload);

        $response->assertStatus(500)
                 ->assertJsonFragment(['error' => 'Unable to create feature.']);
    }
}