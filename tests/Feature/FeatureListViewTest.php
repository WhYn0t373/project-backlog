<?php

namespace Tests\Feature;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class FeatureListViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_features_index_view_displays_features_and_livewire_component()
    {
        $features = Feature::factory()->count(2)->create();

        $view = view('features.index', ['features' => $features]);

        $rendered = $view->render();

        // Check that the table rows contain feature names
        foreach ($features as $feature) {
            $this->assertStringContainsString(
                e($feature->name),
                $rendered,
                "Rendered view should contain feature name '{$feature->name}'"
            );
        }

        // Check that the Livewire component tag is present
        $this->assertStringContainsString(
            '<livewire:feature-form />',
            $rendered,
            'Rendered view should contain the feature-form Livewire component'
        );
    }
}