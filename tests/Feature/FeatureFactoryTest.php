<?php

namespace Tests\Feature;

use App\Models\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_a_valid_feature()
    {
        $feature = Feature::factory()->create();

        $this->assertNotEmpty($feature->name);
        $this->assertIsString($feature->description);
        $this->assertNotNull($feature->created_at);
    }
}