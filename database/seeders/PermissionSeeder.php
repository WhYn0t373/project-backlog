<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Seeder to create default roles and permissions.
 *
 * This seeder creates an `admin` role with full CRUD permissions on features
 * and assigns it to a user created with the factory (if it does not already exist).
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'create features',
            'read features',
            'update features',
            'delete features',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create admin role and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Ensure there is at least one admin user
        $adminEmail = 'admin@example.com';
        $adminUser = User::where('email', $adminEmail)->first();
        if (!$adminUser) {
            $adminUser = User::factory()->create([
                'name' => 'Admin User',
                'email' => $adminEmail,
                'password' => bcrypt('password'), // use secure password in production
            ]);
        }

        // Assign role to user
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}