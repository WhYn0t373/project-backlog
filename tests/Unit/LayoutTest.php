<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LayoutTest extends TestCase
{
    public function test_layout_includes_title_and_content_sections()
    {
        $output = Blade::render(
            <<<BLADE
            @extends('layouts.app')
            @section('title', 'Page Title')
            @section('content')
                <p>Hello World</p>
            @endsection
            BLADE
        );

        $this->assertStringContainsString('<title>Page Title</title>', $output);
        $this->assertStringContainsString('<p>Hello World</p>', $output);
    }
}