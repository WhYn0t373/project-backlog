<?php

namespace Tests\Feature;

use App\Http\Livewire\FeaturesTable;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FeaturesTableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed permissions and assign to admin role
        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    /** @test */
    public function it_displays_features_and_all_columns()
    {
        Feature::factory()->count(3)->create();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeaturesTable::class)
            ->assertSee('Name')
            ->assertSee('Description')
            ->assertSee('Actions');
    }

    /** @test */
    public function it_can_search_features()
    {
        Feature::factory()->create(['name' => 'Unique Feature']);
        Feature::factory()->create(['name' => 'Other Feature']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeaturesTable::class)
            ->set('search', 'Unique')
            ->assertSee('Unique Feature')
            ->assertDontSee('Other Feature');
    }

    /** @test */
    public function it_deletes_a_feature_with_authorization()
    {
        $feature = Feature::factory()->create();

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeaturesTable::class)
            ->call('delete', $feature);

        $this->assertDatabaseMissing('features', ['id' => $feature->id]);
    }

    /** @test */
    public function it_dispatches_open_event_when_create_called()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeaturesTable::class)
            ->call('create')
            ->assertDispatchedBrowserEvent('open-feature-form', ['mode' => 'create']);
    }

    /** @test */
    public function it_dispatches_open_event_with_featureId_when_edit_called()
    {
        $feature = Feature::factory()->create();
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeaturesTable::class)
            ->call('edit', $feature)
            ->assertDispatchedBrowserEvent('open-feature-form', ['mode' => 'edit', 'featureId' => $feature->id]);
    }
}