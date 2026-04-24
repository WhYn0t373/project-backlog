<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ModalTest extends TestCase
{
    public function test_modal_renders_dialog_attributes()
    {
        $output = Blade::render('<x-modal title="Test Modal" id="test-modal">Content</x-modal>');
        $this->assertStringContainsString('role="dialog"', $output);
        $this->assertStringContainsString('aria-modal="true"', $output);
        $this->assertStringContainsString('id="test-modal"', $output);
        $this->assertStringContainsString('aria-labelledby="test-modal-title"', $output);
        $this->assertStringContainsString('Test Modal', $output);
        $this->assertStringContainsString('Content', $output);
    }

    public function test_modal_x_show_binding_is_present()
    {
        $output = Blade::render('<x-modal title="Test Modal" id="test-modal">Content</x-modal>');
        $this->assertStringContainsString('x-show="open"', $output);
    }
}