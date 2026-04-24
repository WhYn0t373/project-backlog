<?php

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ListTest extends TestCase
{
    public function test_list_merges_custom_classes()
    {
        $output = Blade::render('<x-list class="my-list"><li>Item</li></x-list>');
        $this->assertStringContainsString('role="list" class="space-y-2 my-list"', $output);
        $this->assertStringContainsString('<li>Item</li>', $output);
    }
}