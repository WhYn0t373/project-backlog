<?php

namespace Tests\Unit\Http;

use App\Http\Kernel;
use App\Http\Middleware\RequestMetrics;
use Tests\TestCase;
use ReflectionClass;

class KernelTest extends TestCase
{
    public function testRequestMetricsMiddlewareRegisteredInApiGroup()
    {
        $kernel = $this->app->make(Kernel::class);

        $ref = new ReflectionClass($kernel);
        $property = $ref->getProperty('middlewareGroups');
        $property->setAccessible(true);
        $middlewareGroups = $property->getValue($kernel);

        $this->assertArrayHasKey('api', $middlewareGroups, 'API middleware group not defined');

        $apiGroup = $middlewareGroups['api'];

        $this->assertContains(
            RequestMetrics::class,
            $apiGroup,
            'RequestMetrics middleware not registered in api group'
        );
    }
}