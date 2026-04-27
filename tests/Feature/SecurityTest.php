<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Security header tests.
 *
 * Verifies that the application emits the correct security headers
 * and that the CSP header does not allow unsafe inline scripts.
 */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the standard security headers are present.
     *
     * @return void
     */
    public function test_security_headers_present()
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff')
                 ->assertHeader('X-Frame-Options', 'DENY')
                 ->assertHeader('X-XSS-Protection', '1; mode=block')
                 ->assertHeader('Referrer-Policy', 'no-referrer');
    }

    /**
     * Test that the Content‑Security‑Policy header is present
     * and does not contain the 'unsafe-inline' keyword.
     *
     * @return void
     */
    public function test_csp_header_and_unsafe_inline_absence()
    {
        $response = $this->get('/');

        $response->assertHeader('Content-Security-Policy', function ($header) {
            // The header should exist and not contain 'unsafe-inline'.
            return $header !== null && strpos($header, "'unsafe-inline'") === false;
        });
    }
}