<?php

namespace Tests\Feature;

use App\Http\Livewire\FeatureForm;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FeatureFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionSeeder::class);
    }

    /** @test */
    public function it_opens_create_mode_and_dispatches_browser_event()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeatureForm::class)
            ->call('open', ['mode' => 'create'])
            ->assertSet('mode', 'create')
            ->assertSet('featureId', null)
            ->assertDispatchedBrowserEvent('show-feature-modal');
    }

    /** @test */
    public function it_opens_edit_mode_and_populates_fields()
    {
        $feature = Feature::factory()->create(['name' => 'Existing', 'description' => 'Desc']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeatureForm::class)
            ->call('open', ['mode' => 'edit', 'featureId' => $feature->id])
            ->assertSet('mode', 'edit')
            ->assertSet('featureId', $feature->id)
            ->assertSet('name', 'Existing')
            ->assertSet('description', 'Desc')
            ->assertDispatchedBrowserEvent('show-feature-modal');
    }

    /** @test */
    public function it_creates_a_feature_on_submit()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeatureForm::class)
            ->call('open', ['mode' => 'create'])
            ->set('name', 'New Feature')
            ->set('description', 'Feature description')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close-feature-modal')
            ->assertDispatched('refreshTable');

        $this->assertDatabaseHas('features', ['name' => 'New Feature']);
    }

    /** @test */
    public function it_updates_a_feature_on_submit()
    {
        $feature = Feature::factory()->create(['name' => 'Old', 'description' => 'Old desc']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeatureForm::class)
            ->call('open', ['mode' => 'edit', 'featureId' => $feature->id])
            ->set('name', 'Updated')
            ->set('description', 'Updated desc')
            ->call('submit')
            ->assertDispatchedBrowserEvent('close-feature-modal')
            ->assertDispatched('refreshTable');

        $this->assertDatabaseHas('features', ['id' => $feature->id, 'name' => 'Updated']);
    }

    /** @test */
    public function it_validates_required_name_on_submit()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Livewire::actingAs($admin)
            ->test(FeatureForm::class)
            ->call('open', ['mode' => 'create'])
            ->set('name', '')
            ->call('submit')
            ->assertHasErrors(['name' => 'required']);
    }
}