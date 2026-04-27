<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\RequestMetrics;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Exception;

class RequestMetricsTest extends TestCase
{
    public function testMiddlewareLogsRequestMetrics()
    {
        // Arrange
        $request  = Request::create('/test', 'GET');
        $response = new Response('OK', 200);

        // Expect File::append to be called with correct path and formatted content
        File::shouldReceive('append')
            ->once()
            ->withArgs(function ($path, $content) {
                // Verify path
                $expectedPath = storage_path('logs/request_metrics.log');
                if ($path !== $expectedPath) {
                    return false;
                }

                // Verify content format: [YYYY-MM-DD HH:MM:SS] GET test 200 12.34ms
                $pattern = '/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] GET test 200 \d+\.\d{2}ms$/';
                return preg_match($pattern, $content) === 1;
            });

        $middleware = new RequestMetrics();

        // Act
        $next = function ($req) use ($response) {
            return $response;
        };

        $middleware->handle($request, $next);
        // Assert handled by the callback in shouldReceive
    }

    public function testMiddlewareHandlesFileAppendException()
    {
        // Arrange
        $request  = Request::create('/test', 'GET');
        $response = new Response('OK', 200);

        // Simulate File::append throwing an exception
        File::shouldReceive('append')
            ->once()
            ->andThrow(new Exception('File error'));

        // Expect Log::error to capture the failure
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return strpos($message, 'Failed to write request metrics') !== false
                    && strpos($message, 'File error') !== false;
            });

        $middleware = new RequestMetrics();

        // Act
        $next = function ($req) use ($response) {
            return $response;
        };

        $middleware->handle($request, $next);
    }
}