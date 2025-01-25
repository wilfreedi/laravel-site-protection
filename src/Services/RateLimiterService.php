<?php

namespace Wilfreedi\SiteProtection\Services;

use Illuminate\Support\Facades\Cache;

class RateLimiterService {

    private static string $keyRateLimit = 'rate_limit';
    private static string $key404Errors = '404_errors';

    public static function check($request, $config): bool {
        $ip = $request->ip();

        if (self::isRateLimited($ip, $config)) {
            return true;
        }

        if (self::hasTooMany404Errors($ip, $config)) {
            return true;
        }

        return false;
    }

    public static function isRateLimited($ip, $config): bool {
        $cacheKey = self::$keyRateLimit . '.' . $ip;

        $hits = Cache::increment($cacheKey);
        Cache::put($cacheKey, $hits, now()->addSeconds($config['rate_limiting']['time']));

        if ($hits > $config['rate_limiting']['max_requests']) {
            return true;
        }

        return false;
    }

    public static function hasTooMany404Errors($ip, $config): bool {
        $cacheKey = self::$key404Errors . '.' . $ip;

        $hits = Cache::get($cacheKey, 0);

        if ($hits > $config['404_protection']['max_404_errors']) {
            return true;
        }

        return false;
    }

    public static function increment404Errors($ip): void {
        $cacheKey = self::$key404Errors . '.' . $ip;
        Cache::increment($cacheKey);
    }

}
