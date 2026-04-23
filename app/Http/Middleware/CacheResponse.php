<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to serve cached responses directly when available.
 *
 * @package App\Http\Middleware
 */
class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string   $keySuffix  Optional suffix for cache key.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $keySuffix = '')
    {
        $baseKey = request()->route()->getName() ?? 'unknown_route';
        $cacheKey = $baseKey . ($keySuffix ? "_{$keySuffix}" : '');

        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);

            return response($cachedResponse, Response::HTTP_OK);
        }

        return $next($request);
    }
}