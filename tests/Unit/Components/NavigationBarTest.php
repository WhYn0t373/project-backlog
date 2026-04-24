<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class NavigationBarTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define dummy routes so Request::routeIs works during Blade rendering
        Route::get('/home', fn () => '')->name('home');
        Route::get('/about', fn () => '')->name('about');
        Route::get('/contact', fn () => '')->name('contact');
    }

    /**
     * Render the navbar component in the context of a request.
     */
    private function renderNavbar(string $uri): string
    {
        // Dispatch a request so that Request::routeIs sees the current route
        Route::dispatch(request()->create($uri));
        return Blade::render('<x-navbar />');
    }

    public function test_home_link_has_aria_current_on_home_route()
    {
        $output = $this->renderNavbar('/home');
        $this->assertStringContainsString('href="/"', $output);
        $this->assertStringContainsString('aria-current="page"', $output);
    }

    public function test_about_link_has_aria_current_on_about_route()
    {
        $output = $this->renderNavbar('/about');
        $this->assertStringContainsString('aria-current="page"', $output);
    }

    public function test_contact_link_has_aria_current_on_contact_route()
    {
        $output = $this->renderNavbar('/contact');
        $this->assertStringContainsString('aria-current="page"', $output);
    }
}