<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FeatureMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function features_table_has_expected_columns_and_types()
    {
        // Ensure a clean state before running the specific migration
        Schema::dropIfExists('features');

        // Run the migration directly
        $this->artisan('migrate', [
            '--path' => database_path('migrations/2026_04_24_000000_create_features_table.php')
        ]);

        $this->assertTrue(Schema::hasTable('features'));

        $expectedColumns = ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at'];
        foreach ($expectedColumns as $column) {
            $this->assertTrue(Schema::hasColumn('features', $column), "Missing column: {$column}");
        }

        // Verify column types
        $this->assertEquals('boolean', Schema::getColumnType('features', 'is_active'));
    }
}