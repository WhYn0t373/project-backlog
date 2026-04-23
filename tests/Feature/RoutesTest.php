<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesTest extends TestCase
{
    /** @test */
    public function home_route_returns_home_view_and_is_named_home()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertSee('Welcome to the Home Page');

        // Ensure the route is named 'home' and resolves to '/'
        $this->assertEquals('/', route('home'));
    }
}