<?php

namespace Tests\Feature\Views;

use Tests\TestCase;

class HomeViewTest extends TestCase
{
    /** @test */
    public function home_view_contains_navbar_and_expected_content()
    {
        $response = $this->get('/');

        // Ensure the page title and content are present
        $response->assertSee('Welcome to the Home Page');
        $response->assertSee('This page demonstrates the base layout and navigation component.');

        // Assert that the navbar brand is rendered
        $response->assertSee('MyApp', false);
    }
}