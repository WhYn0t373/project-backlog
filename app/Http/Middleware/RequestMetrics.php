<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * RequestMetrics middleware records the time taken to process each API request
 * and logs the elapsed time along with the HTTP status code.
 *
 * The log is written to `storage/logs/request_metrics.log`. Each line contains
 * a timestamp, HTTP method, request path, status code, and duration in milliseconds.
 */
class RequestMetrics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Pass the request to the next middleware / controller
        $response = $next($request);

        // Calculate duration in milliseconds
        $durationMs = round((microtime(true) - $startTime) * 1000, 2);

        // Gather log data
        $timestamp = now()->toDateTimeString();
        $method    = $request->method();
        $uri       = $request->path();
        $status    = $response->getStatusCode();

        $logLine = sprintf(
            '[%s] %s %s %d %.2fms',
            $timestamp,
            $method,
            $uri,
            $status,
            $durationMs
        );

        // Write to log file, creating it if necessary
        try {
            $logFile = storage_path('logs/request_metrics.log');
            File::append($logFile, $logLine . PHP_EOL);
        } catch (\Throwable $e) {
            // Fail silently but record the error in Laravel's default log
            Log::error('Failed to write request metrics: ' . $e->getMessage());
        }

        return $response;
    }
}