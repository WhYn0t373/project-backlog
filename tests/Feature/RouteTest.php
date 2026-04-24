<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_all_feature_resource_routes()
    {
        $this->assertTrue(\Route::has('features.index'));
        $this->assertTrue(\Route::has('features.create'));
        $this->assertTrue(\Route::has('features.store'));
        $this->assertTrue(\Route::has('features.show'));
        $this->assertTrue(\Route::has('features.edit'));
        $this->assertTrue(\Route::has('features.update'));
        $this->assertTrue(\Route::has('features.destroy'));
    }
}