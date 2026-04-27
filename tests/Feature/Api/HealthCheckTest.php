<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function testHealthEndpointReturnsOk()
    {
        $response = $this->getJson('/api/health');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'ok',
            ]);
    }
}