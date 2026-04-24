<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\FeatureForm;
use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FeatureFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_feature_on_submit()
    {
        Livewire::test(FeatureForm::class)
            ->set('name', 'New Feature')
            ->set('description', 'Description of new feature')
            ->call('submit')
            ->assertEmitted('featureUpdated')
            ->assertEmitted('closeModal')
            ->assertSet('name', '')
            ->assertSet('description', null);

        $this->assertDatabaseHas('features', [
            'name' => 'New Feature',
            'description' => 'Description of new feature',
        ]);
    }

    /** @test */
    public function it_updates_an_existing_feature_on_submit()
    {
        $feature = Feature::factory()->create([
            'name' => 'Old Name',
            'description' => 'Old description',
        ]);

        Livewire::test(FeatureForm::class, ['id' => $feature->id])
            ->assertSet('name', 'Old Name')
            ->assertSet('description', 'Old description')
            ->set('name', 'Updated Name')
            ->set('description', 'Updated description')
            ->call('submit')
            ->assertEmitted('featureUpdated')
            ->assertEmitted('closeModal')
            ->assertSet('name', '')
            ->assertSet('description', null);

        $this->assertDatabaseHas('features', [
            'id' => $feature->id,
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ]);
    }

    /** @test */
    public function it_requires_name_on_submit()
    {
        Livewire::test(FeatureForm::class)
            ->set('name', '')
            ->set('description', 'Some description')
            ->call('submit')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function it_resets_when_mounting_with_invalid_id()
    {
        Livewire::test(FeatureForm::class, ['id' => 9999])
            ->assertSet('featureId', null)
            ->assertSet('name', '')
            ->assertSet('description', null);
    }
}