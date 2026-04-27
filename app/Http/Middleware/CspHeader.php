<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to emit a Content‑Security‑Policy (CSP) header.
 *
 * The CSP is configured via the `config/csp.php` file.  By default it disallows
 * inline scripts (`'unsafe-inline'`), ensures only resources from the same
 * origin are loaded and blocks all plugins.
 *
 * The middleware respects the `APP_CSP_ENABLED` environment variable, allowing
 * CSP to be disabled in non‑production environments.
 */
class CspHeader
{
    /**
     * Handle an incoming request and add the CSP header.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Enable CSP only if APP_CSP_ENABLED=true
        // env() returns a string; convert it to a boolean.
        $enabled = env('APP_CSP_ENABLED', false);
        $enabled = filter_var($enabled, FILTER_VALIDATE_BOOLEAN);

        if (! $enabled) {
            return $response;
        }

        // Fetch CSP directives from config, fallback to hard‑coded default if missing.
        $directives = config(
            'csp.directives',
            "default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none'; base-uri 'self';"
        );

        // Ensure the header is not already present.
        if (!$response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $directives);
        }

        return $response;
    }
}