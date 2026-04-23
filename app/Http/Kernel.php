<?php

namespace App\Http;

use App\Http\Middleware\CacheResponse;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Application HTTP kernel.
 *
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * Global middleware stack.
     *
     * @var array<int, string>
     */
    protected $middleware = [
        // Existing global middleware...
    ];

    /**
     * Route middleware groups.
     *
     * @var array<string, array<int, string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Existing web middleware...
        ],

        'api' => [
            // Apply rate‑limit middleware to all API routes
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware.
     *
     * @var array wszystkich middleware...
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'cache.response' => CacheResponse::class,
        // Other route middleware...
    ];
}