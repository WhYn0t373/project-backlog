<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_image_has_src_and_alt()
    {
        $output = Blade::render('<x-image src="/images/pic.jpg" alt="Picture" class="w-32 h-32" />');
        $this->assertStringContainsString('src="/images/pic.jpg"', $output);
        $this->assertStringContainsString('alt="Picture"', $output);
        $this->assertStringContainsString('class="w-32 h-32"', $output);
    }

    public function test_image_without_alt_defaults_to_empty()
    {
        $output = Blade::render('<x-image src="/images/pic.jpg" />');
        $this->assertStringContainsString('alt=""', $output);
    }
}