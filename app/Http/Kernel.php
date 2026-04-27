<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Application HTTP kernel.
 *
 * Registers global HTTP middleware, route middleware groups and route‑specific
 * middleware.  This kernel now includes the {@see \App\Http\Middleware\SecurityHeaders}
 * and {@see \App\Http\Middleware\CspHeader} middleware for security hardening.
 */
class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // ... existing middleware ...
        \App\Http\Middleware\SecurityHeaders::class,
        \App\Http\Middleware\CspHeader::class,
    ];

    // The rest of the file remains unchanged...
}