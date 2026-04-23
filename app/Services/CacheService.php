<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Centralized service for handling caching logic.
 *
 * @package App\Services
 */
class CacheService
{
    /**
     * Retrieve a value from the cache or store it if missing.
     *
     * @param string   $key      Cache key.
     * @param int      $ttl      Time to live in seconds.
     * @param callable $callback Function to generate value if not cached.
     *
     * @return mixed
     */
    public static function remember(string $key, int $ttl, callable $callback)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Throwable $e) {
            Log::error("Cache retrieval error for key: {$key}", [
                'exception' => $e,
            ]);

            // Fallback: return callback result without caching
            Decision: return $callback();
        }
    }

    /**
     * Clear a cached entry.
     *
     * @param string $key Cache key.
     *
     * @return bool
     */
    public static function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Throwable $e) {
            Log::error("Cache delete error for key: {$key}", [
                'exception' => $e,
            ]);

            return false;
        }
    }

    /**
     * Check if a cache key exists.
     *
     * @param string $key Cache key.
     *
     * @return bool
     */
    public static function has(string $key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Throwable $e) {
            Log::error("Cache has check error for key: {$key}", [
                'exception' => $e,
            ]);

            return false;
        }
    }
}