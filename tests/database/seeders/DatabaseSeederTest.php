<?php

namespace Tests\Database\Seeders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function database_seeder_calls_item_seeder()
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);

        $this->assertDatabaseCount('items', 3);
    }
}