<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\FeatureForm;
use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FeatureFormViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_create_feature_title_when_no_id()
    {
        Livewire::test(FeatureForm::class)
            ->assertSee('Create Feature')
            ->assertDontSee('Edit Feature');
    }

    /** @test */
    public function it_displays_edit_feature_title_when_id_is_provided()
    {
        $feature = Feature::factory()->create();

        Livewire::test(FeatureForm::class, ['id' => $feature->id])
            ->assertSee('Edit Feature')
            ->assertDontSee('Create Feature');
    }

    /** @test */
    public function it_contains_name_and_description_fields()
    {
        Livewire::test(FeatureForm::class)
            ->assertSee('name="name"', false) // raw attribute check
            ->assertSee('name="description"', false);
    }
}