<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

/**
 * Integration tests for the key conversion endpoint.
 *
 * This test suite verifies that the key conversion endpoint:
 *   1. Successfully processes a valid payload.
 *   2. Enforces rate limiting by returning a 429 status code when the limit is exceeded.
 *   3. Handles multiple concurrent requests without errors or timeouts.
 *
 * The tests use Laravel's RefreshDatabase trait to ensure a clean database state
 * and rely on Laravel's HTTP testing helpers for synchronous requests.
 * For concurrency testing, we send several requests sequentially because true
 * concurrency is difficult to simulate reliably in a PHPUnit environment.
 */
class KeyConversionIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The URL path for the key conversion endpoint.
     *
     * Adjust this constant to match the actual route used by the application.
     *
     * @var string
     */
    private const ENDPOINT = '/api/key-conversion';

    /**
     * Build a valid payload for the key conversion request.
     *
     * @return array
     */
    private function getValidPayload(): array
    {
        // Replace these fields with the actual required keys for your endpoint.
        return [
            'source_key'    => 'exampleSourceKey123',
            'target_format' => 'json',
            'data'          => ['foo' => 'bar', 'baz' => 42],
        ];
    }

    /**
     * Test that a valid payload is successfully converted.
     *
     * @return void
     */
    public function testValidPayloadConversionSucceeds(): void
    {
        $payload = $this->getValidPayload();

        $response = $this->postJson(self::ENDPOINT, $payload);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'converted_key',
                'original_key',
                'status',
                // Add additional keys that are expected in the response.
            ]);

        // Optionally verify the content of the response.
        $response->assertJsonFragment([
            'original_key' => $payload['source_key'],
        ]);
    }

    /**
     * Test that the rate limiter returns a 429 status when the limit is exceeded.
     *
     * @return void
     */
    public function testRateLimitReturns429(): void
    {
        // Override the rate limit configuration for the test environment.
        // The exact configuration key may vary depending on the application.
        // Ensure this key matches the implementation of your rate limiter.
        Config::set('services.rate_limit', 1);

        $payload = $this->getValidPayload();

        // First request should succeed.
        $firstResponse = $this->postJson(self::ENDPOINT, $payload);
        $firstResponse->assertStatus(200);

        // Second request should hit the rate limit.
        $secondResponse = $this->postJson(self::ENDPOINT, $payload);
        $secondResponse->assertStatus(429);

        // Verify the error message (adjust key if your API returns a different structure).
        $secondResponse->assertJsonFragment([
            'message' => 'Too Many Requests',
        ]);
    }

    /**
     * Test that the endpoint can handle multiple requests without errors.
     *
     * Note: True concurrent requests are difficult to simulate in a PHPUnit
     * environment. We instead send a series of requests sequentially to
     * confirm the endpoint remains responsive under load.
     *
     * @return void
     */
    public function testConcurrencyHandlesMultipleRequests(): void
    {
        $payload = $this->getValidPayload();

        // Send 10 sequential POST requests and assert each one succeeds.
        for ($i = 0; $i < 10; $i++) {
            $response = $this->postJson(self::ENDPOINT, $payload);

            $response->assertStatus(200);

            $body = $response->json();

            $this->assertArrayHasKey('converted_key', $body, "Request #{$i} missing 'converted_key'.");
        }
    }
}