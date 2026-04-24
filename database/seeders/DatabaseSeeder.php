<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder is the primary seeder class.
 *
 * It is responsible for calling all individual seeders.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This method registers the ItemSeeder to run during the seeding process.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(ItemSeeder::class);
    }
}