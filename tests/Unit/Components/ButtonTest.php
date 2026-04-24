<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ButtonTest extends TestCase
{
    public function test_button_renders_with_custom_type_and_label()
    {
        $output = Blade::render('<x-button type="submit" label="Save" />');
        $this->assertStringContainsString('<button type="submit"', $output);
        $this->assertStringContainsString('aria-label="Save"', $output);
        $this->assertStringContainsString('Save', $output);
    }

    public function test_button_merges_custom_classes()
    {
        $output = Blade::render('<x-button class="custom-class">Click</x-button>');
        $this->assertStringContainsString('class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-sm bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 custom-class"', $output);
    }
}