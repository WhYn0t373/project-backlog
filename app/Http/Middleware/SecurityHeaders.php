<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to add standard security headers to every response.
 *
 * Adds the following headers:
 *  - X-Content-Type-Options: nosniff
 *  - X-Frame-Options: DENY
 *  - X-XSS-Protection: 1; mode=block
 *  - Referrer-Policy: no-referrer
 *
 * These headers mitigate MIME‑type sniffing, click‑jacking, basic XSS protection
 * and referrer leakage respectively.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request and append security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Ensure headers are not already set (avoid duplicate headers).
        if (!$response->headers->has('X-Content-Type-Options')) {
            $response->headers->set('X-Content-Type-Options', 'nosniff');
        }
        if (!$response->headers->has('X-Frame-Options')) {
            $response->headers->set('X-Frame-Options', 'DENY');
        }
        if (!$response->headers->has('X-XSS-Protection')) {
            $response->headers->set('X-XSS-Protection', '1; mode=block');
        }
        if (!$response->headers->has('Referrer-Policy')) {
            $response->headers->set('Referrer-Policy', 'no-referrer');
        }

        return $response;
    }
}