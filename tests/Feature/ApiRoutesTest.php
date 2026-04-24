<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function routes_are_defined_with_correct_middleware()
    {
        // Check delete feature route
        $request = Request::create('/features/1', 'DELETE');
        $route = Route::getRoutes()
                      ->match($request);

        $this->assertNotNull($route);
        $this->assertTrue(in_array('auth:sanctum', $route->gatherMiddleware()));
        $this->assertTrue(in_array('role', $route->gatherMiddleware()));
    }

    /** @test */
    public function conversion_route_is_protected_by_role_middleware()
    {
        $request = Request::create('/conversion', 'POST');
        $route = Route::getRoutes()
                      ->match($request);

        $this->assertNotNull($route);
        $this->assertTrue(in_array('auth:sanctum', $route->gatherMiddleware()));
        $this->assertTrue(in_array('role', $route->gatherMiddleware()));
    }
}