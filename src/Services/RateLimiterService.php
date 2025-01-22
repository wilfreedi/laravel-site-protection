<?php

namespace Wilfreedi\SiteProtection\Services;

use Illuminate\Support\Facades\Cache;

class RateLimiterService
{

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

    public static function isRateLimited(string $ip, array $config): bool {
        $rateLimitKey = "rate_limit:{$ip}";
        $hits = Cache::increment($rateLimitKey);
        Cache::put($rateLimitKey, $hits, now()->addSeconds(1));

        if ($hits > $config['rate_limiting']['max_requests_per_second']) {
            return true;
        }

        return false;
    }

    public static function hasTooMany404Errors(string $ip, array $config): bool {
        $cacheKey = "404_errors:{$ip}";
        $errors = Cache::get($cacheKey, 0);


//        if ($request->isMethod('GET') && $request->getStatusCode() === 404) {
//            Cache::put($cacheKey, $errors + 1, now()->addMinutes(5));
//            if ($errors + 1 > $config['max_404_errors']) {
//                return Redirect::to('/captcha');
//            }
//        }

        if ($errors > $config['404_protection']['max_404_errors']) {
            Cache::put("blocked_ip:{$ip}", true, now()->addMinutes($config['404_protection']['block_time_minutes']));
            return true;
        }

        return false;
    }

}
