<?php

namespace Tests\Database\Seeders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function item_seeder_inserts_three_items()
    {
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\ItemSeeder']);

        $this->assertDatabaseCount('items', 3);

        $this->assertDatabaseHas('items', [
            'name' => 'Project Plan',
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Design Review',
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Sprint Backlog',
        ]);
    }
}