<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebRouteTest extends TestCase
{
    /** @test */
    public function root_route_returns_welcome_view()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('welcome');
        $response->assertSee('Welcome to Laravel 10!');
    }
}