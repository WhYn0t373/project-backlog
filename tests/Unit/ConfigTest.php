<?php

namespace Tests\Unit;

use Tests\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function config_app_defaults_are_set()
    {
        $this->assertSame('Laravel', config('app.name'));
        $this->assertSame('local', config('app.env'));
        $this->assertTrue(config('app.debug'));
        $this->assertSame('http://localhost', config('app.url'));
    }

    /** @test */
    public function database_default_connection_is_sqlite()
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(database_path('database.sqlite'), config('database.connections.sqlite.database'));
    }
}