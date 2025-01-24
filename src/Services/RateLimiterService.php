<?php

namespace Wilfreedi\SiteProtection\Services;

use Illuminate\Support\Facades\Cache;

class RateLimiterService {

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
        $cacheKey = "rate_limit:{$ip}";

        $hits = Cache::increment($cacheKey);
        Cache::put($cacheKey, $hits, now()->addSeconds(1));

        if ($hits > $config['rate_limiting']['max_requests_per_second']) {
            return true;
        }

        return false;
    }

    public static function hasTooMany404Errors($request, $config): bool {
        $ip = $request->ip();
        $cacheKey = "404_errors:{$ip}";

        $hits = Cache::increment($cacheKey);
        if ($request->isMethod('GET') && $request->getStatusCode() === 404) {
            Cache::put($cacheKey, $hits, now()->addMinutes(5));
        }

        if ($hits > $config['404_protection']['max_404_errors']) {
            return true;
        }

        return false;
    }

}
