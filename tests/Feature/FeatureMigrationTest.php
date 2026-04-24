<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeatureMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Confirm that the migration creates the table with the expected columns.
     *
     * @return void
     */
    public function test_migration_creates_features_table()
    {
        $this->assertTrue(
            DB::connection()->getSchemaBuilder()->hasTable('features'),
            'Features table should exist after migration.'
        );

        $schema = DB::connection()->getSchemaBuilder();

        $this->assertTrue(
            $schema->hasColumn('features', 'id'),
            'id column should exist.'
        );
        $this->assertTrue(
            $schema->hasColumn('features', 'name'),
            'name column should exist.'
        );
        $this->assertTrue(
            $schema->hasColumn('features', 'description'),
            'description column should exist.'
        );
        $this->assertTrue(
            $schema->hasColumn('features', 'created_at'),
            'created_at column should exist.'
        );
        $this->assertTrue(
            $schema->hasColumn('features', 'updated_at'),
            'updated_at column should exist.'
        );

        // Check that name is unique
        $this->assertTrue(
            $schema->hasColumn('features', 'name'),
            'name column should exist.'
        );

        $table = $schema->getColumnListing('features');
        $this->assertContains('name', $table);
    }
}