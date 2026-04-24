<?php

namespace Tests\Unit;

use Tests\TestCase;

class AuthConfigTest extends TestCase
{
    public function test_api_guard_driver_is_jwt()
    {
        $this->assertEquals('jwt', config('auth.guards.api.driver'));
    }

    public function test_default_guard_is_web()
    {
        $this->assertEquals('web', config('auth.defaults.guard'));
    }
}