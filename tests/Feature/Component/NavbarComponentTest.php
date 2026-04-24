<?php

namespace Tests\Feature\Component;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class NavbarComponentTest extends TestCase
{
    /** @test */
    public function it_displays_brand_and_links()
    {
        Route::get('/', fn () => null)->name('home');
        Route::get('/about', fn () => null)->name('about');
        Route::get('/dashboard', fn () => null)->name('dashboard');

        $response = $this->blade('<x-navbar />');

        $response->assertSee('MyApp');
        $response->assertSee('<a href="/about" class="navbar-link">About</a>', false);
        $response->assertSee('<a href="/dashboard" class="navbar-link">Dashboard</a>', false);
    }

    /** @test */
    public function it_applies_active_class_to_current_route_link()
    {
        Route::get('/', fn () => null)->name('home');
        Route::get('/about', fn () => null)->name('about');
        Route::get('/dashboard', fn () => null)->name('dashboard');

        // Simulate being on the 'about' route
        $this->get('/about');

        $response = $this->blade('<x-navbar />');

        $response->assertSee('<a href="/about" class="navbar-link active">About</a>', false);
        $response->assertDontSee('class="navbar-link active" href="/home"', false);
        $response->assertDontSee('class="navbar-link active" href="/dashboard"', false);
    }

    /** @test */
    public function it_displays_user_role_when_authenticated()
    {
        Route::get('/', fn () => null)->name('home');

        // Create a simple stdClass user with a role property for testing
        $user = (object) ['role' => 'admin'];
        Auth::login($user);

        $response = $this->blade('<x-navbar />');

        $response->assertSee('Role: admin');
    }

    /** @test */
    public function it_hides_user_role_when_unauthenticated()
    {
        Route::get('/', fn () => null)->name('home');

        $response = $this->blade('<x-navbar />');

        $response->assertDontSee('Role:', false);
    }
}