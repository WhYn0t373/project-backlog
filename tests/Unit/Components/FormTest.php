<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FormTest extends TestCase
{
    public function test_form_merges_custom_classes()
    {
        $output = Blade::render('<x-form class="my-form"><input /></x-form>');
        $this->assertStringContainsString('class="space-y-6 my-form"', $output);
        $this->assertStringContainsString('<input />', $output);
    }
}