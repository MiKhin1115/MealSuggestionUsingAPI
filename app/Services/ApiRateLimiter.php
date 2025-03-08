<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiRateLimiter
{
    /**
     * The maximum number of requests allowed per minute
     */
    protected $maxRequestsPerMinute;

    /**
     * The cache key prefix for rate limiting
     */
    protected $cachePrefix = 'api_rate_limit:';

    /**
     * Create a new rate limiter instance
     */
    public function __construct(int $maxRequestsPerMinute = 5)
    {
        $this->maxRequestsPerMinute = $maxRequestsPerMinute;
    }

    /**
     * Check if the API request is allowed based on rate limiting
     *
     * @param string $endpoint The API endpoint being accessed
     * @return bool Whether the request is allowed
     */
    public function allowRequest(string $endpoint): bool
    {
        $cacheKey = $this->cachePrefix . $endpoint;
        
        // Get the current request count for this endpoint - use file cache
        $requestCount = Cache::store('file')->get($cacheKey, 0);
        
        // If we've reached the limit, deny the request
        if ($requestCount >= $this->maxRequestsPerMinute) {
            Log::warning("API rate limit exceeded for endpoint: {$endpoint}");
            return false;
        }
        
        // Increment the request count and set it to expire after 1 minute - use file cache
        Cache::store('file')->put($cacheKey, $requestCount + 1, now()->addMinute());
        
        return true;
    }

    /**
     * Get the remaining requests allowed for an endpoint
     *
     * @param string $endpoint The API endpoint
     * @return int The number of remaining requests
     */
    public function remainingRequests(string $endpoint): int
    {
        $cacheKey = $this->cachePrefix . $endpoint;
        $requestCount = Cache::store('file')->get($cacheKey, 0);
        
        return max(0, $this->maxRequestsPerMinute - $requestCount);
    }

    /**
     * Get the time until rate limit resets (in seconds)
     *
     * @param string $endpoint The API endpoint
     * @return int|null Seconds until reset, or null if no limit is active
     */
    public function timeUntilReset(string $endpoint): ?int
    {
        $cacheKey = $this->cachePrefix . $endpoint;
        
        // Check if there's an active rate limit for this endpoint
        if (!Cache::store('file')->has($cacheKey)) {
            return null;
        }
        
        // Since we can't reliably get the exact time left with file cache,
        // we'll return a fixed value when rate limiting is active
        return 60; // Rate limits reset after 1 minute
    }
} 