<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Handles CSRF token verification and ensures the XSRF cookie
 * is added to every response that requires it.
 *
 * The parent implementation already provides the core logic,
 * but this subclass ensures the cookie is refreshed on each
 * successful request, which is essential for stateful API
 * interactions during testing.
 */
class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add URIs that should bypass CSRF protection if needed.
    ];

    /**
     * Add the CSRF cookie to the response if necessary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Http\Response
     */
    protected function addCookiesToResponse($request, $response): Response
    {
        // Delegate to the parent implementation, which handles cookie
        // refreshing and setting the XSRF cookie in the response.
        return parent::addCookiesToResponse($request, $response);
    }
}