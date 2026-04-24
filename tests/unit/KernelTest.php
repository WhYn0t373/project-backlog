<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Kernel;
use ReflectionClass;

class KernelTest extends TestCase
{
    public function test_route_middleware_contains_jwt()
    {
        $kernel = new Kernel(app());
        $reflection = new ReflectionClass($kernel);
        $property = $reflection->getProperty('routeMiddleware');
        $property->setAccessible(true);
        $routeMiddleware = $property->getValue($kernel);

        $this->assertArrayHasKey('jwt', $routeMiddleware);
        $this->assertEquals(
            \App\Http\Middleware\JwtMiddleware::class,
            $routeMiddleware['jwt']
        );
    }
}