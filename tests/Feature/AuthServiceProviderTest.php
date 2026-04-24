<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Policies\FeaturePolicy;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceProviderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_feature_policy()
    {
        $policy = Gate::getPolicyFor(Feature::class);
        $this->assertInstanceOf(FeaturePolicy::class, $policy);
    }
}