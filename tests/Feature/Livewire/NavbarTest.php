<?php

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_and_renders_view()
    {
        Livewire::test('navbar')
            ->assertViewIs('livewire.navbar')
            ->assertSee('MyApp')
            ->assertSee('Home')
            ->assertSee('Features')
            ->assertSee('Pricing')
            ->assertSee('Login')
            ->assertSee('Sign Up');
    }

    /** @test */
    public function it_contains_expected_navbar_structure_in_home_page()
    {
        $response = $this->get('/');

        // Check for nav tag and essential classes
        $response->assertSee('<nav', false);
        $response->assertSee('navbar-brand', false);
        $response->assertSee('navbar-toggler', false);
        $response->assertSee('navbar-nav', false);
    }
}