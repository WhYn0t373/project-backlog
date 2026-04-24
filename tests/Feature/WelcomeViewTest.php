<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeViewTest extends TestCase
{
    /** @test */
    public function welcome_view_can_be_rendered()
    {
        $view = view('welcome')->render();

        $this->assertStringContainsString('Welcome to Laravel 10!', $view);
        $this->assertStringContainsString('<title>Laravel</title>', $view);
    }
}