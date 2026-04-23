<?php
/**
 * Metrics Endpoint Test
 *
 * @package Tests\Feature
 */

namespace Tests\Feature;

use Tests\TestCase;

class MetricsTest extends TestCase
{
    /**
     * Test that /metrics endpoint returns 200 and contains the custom metric.
     *
     * @return void
     */
    public function test_metrics_endpoint_returns_success_and_metric()
    {
        $response = $this->get('/metrics');

        $response->assertStatus(200)
                 ->assertSee('app_requests_total');
    }
}