<?php

namespace Tests\Feature\Component;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class LayoutAppTest extends TestCase
{
    /** @test */
    public function layout_includes_navbar_and_yields_content()
    {
        Route::get('/', function () {
            // Render the layout with a dummy section
            return $this->blade('@extends("layouts.app") @section("content")Test Content@endsection');
        });

        $response = $this->get('/');

        $response->assertSee('MyApp', false); // Navbar brand
        $response->assertSee('Test Content', false); // Yielded content
    }
}