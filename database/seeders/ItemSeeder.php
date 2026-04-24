<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * Seed the items table with initial data.
 *
 * Adds three sample items with realistic names and descriptions.
 */
class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Project Plan',
                'description' => 'Outline the scope, milestones, and deliverables.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Design Review',
                'description' => 'Review the UI/UX designs with stakeholders.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sprint Backlog',
                'description' => 'List of tasks planned for the current sprint.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('items')->insert($items);
    }
}