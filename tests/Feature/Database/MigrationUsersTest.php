<?php

namespace Tests\Feature\Database;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrationUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_has_expected_columns_and_unique_email_constraint()
    {
        // Migration is run automatically with RefreshDatabase
        $this->assertTrue(Schema::hasTable('users'), 'users table does not exist');

        $columns = Schema::getColumnListing('users');
        $expected = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        foreach ($expected as $col) {
            $this->assertContains(
                $col,
                $columns,
                "Missing expected column [$col] in users table"
            );
        }

        // Check that email is unique
        $this->assertTrue(
            Schema::hasColumn('users', 'email'),
            'email column not found'
        );

        // Laravel automatically creates unique index on email in migration
        // We confirm by inserting duplicate email which should fail
        \DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        \DB::table('users')->insert([
            'name' => 'Another User',
            'email' => 'test@example.com', // duplicate
            'password' => bcrypt('secret'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}