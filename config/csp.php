<?php

/**
 * CSP configuration
 *
 * This file defines the default Content‑Security‑Policy directives used by the
 * {@see \App\Http\Middleware\CspHeader} middleware.  The directives are
 * intentionally restrictive:
 *
 *   - default-src 'self'
 *   - script-src  'self'
 *   - style-src   'self'
 *   - object-src  'none'
 *   - base-uri    'self'
 *
 * Adjust these directives to suit your deployment needs.  They are used as a
 * single string; if you need separate directive arrays, extend this file
 * accordingly.
 */

return [
    'directives' => "default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none'; base-uri 'self';",
];