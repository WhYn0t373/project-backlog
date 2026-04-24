<?php

namespace Tests\Database\Migrations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ItemsTableMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function items_table_has_expected_columns()
    {
        // Migrations are automatically run by RefreshDatabase, so this call is optional.
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('items'), 'The items table does not exist.');

        $this->assertTrue(Schema::hasColumn('items', 'id'), 'The id column is missing.');
        $this->assertTrue(Schema::hasColumn('items', 'name'), 'The name column is missing.');
        $this->assertTrue(Schema::hasColumn('items', 'description'), 'The description column is missing.');
        $this->assertTrue(Schema::hasColumn('items', 'created_at'), 'The created_at column is missing.');
        $this->assertTrue(Schema::hasColumn('items', 'updated_at'), 'The updated_at column is missing.');
    }
}