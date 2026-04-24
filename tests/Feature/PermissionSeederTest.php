<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_permissions_and_admin_role()
    {
        $this->seed(\Database\Seeders\PermissionSeeder::class);

        foreach (['create features', 'read features', 'update features', 'delete features'] as $perm) {
            $this->assertDatabaseHas('permissions', ['name' => $perm]);
        }

        $this->assertDatabaseHas('roles', ['name' => 'admin']);

        $role = Role::where('name', 'admin')->first();
        $this->assertTrue($role->hasPermissionTo('create features'));
        $this->assertTrue($role->hasPermissionTo('read features'));
        $this->assertTrue($role->hasPermissionTo('update features'));
        $this->assertTrue($role->hasPermissionTo('delete features'));
    }
}