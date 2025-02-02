<?php

namespace Wilfreedi\SiteProtection\Services;

use Wilfreedi\SiteProtection\Helpers\CacheHelper;

class RateLimiterService {

    private static string $keyRateLimit = 'rate_limit';
    private static string $keyRateLimitAll = 'rate_limit_all';
    private static string $key404Errors = '404_errors';
    private static string $keyJSValidate = 'js_validate';

    public static function check($request, $config): bool {
        $ip = $request->ip();

        if (self::isRateLimited($ip, $config)) {
            return true;
        }

        if (self::hasTooMany404Errors($ip, $config)) {
            return true;
        }

        if (self::isJSValidate($ip, $config)) {
            return true;
        }

        return false;
    }

    public static function incrementRateLimitAll($ip): void {
        $cacheKey = self::$keyRateLimitAll . '.' . $ip;
        CacheHelper::increment($cacheKey);
    }

    public static function isRateLimited($ip, $config): bool {
        $cacheKey = self::$keyRateLimit . '.' . $ip;

        $hits = CacheHelper::increment($cacheKey, now()->addSeconds($config['rate_limiting']['time']));

        if ($hits > $config['rate_limiting']['max_requests']) {
            return true;
        }

        return false;
    }

    public static function hasTooMany404Errors($ip, $config): bool {
        $cacheKey = self::$key404Errors . '.' . $ip;

        $hits = CacheHelper::get($cacheKey);

        if ($hits > $config['404_protection']['max_404_errors']) {
            return true;
        }

        return false;
    }

    public static function isJSValidate($ip, $config): bool {
        if($config['js']['enabled']) {
            $cacheKeyJS = self::$keyJSValidate . '.' . $ip;
            $cacheKey = self::$keyRateLimitAll . '.' . $ip;

            $jsValidate = CacheHelper::get($cacheKeyJS);
            if (!$jsValidate) {
                $hits = CacheHelper::get($cacheKey);

                if ($hits >= 2) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function increment404Errors($ip): void {
        $cacheKey = self::$key404Errors . '.' . $ip;
        CacheHelper::increment($cacheKey);
    }
    public static function incrementJSValidate($ip): void {
        $cacheKey = self::$keyJSValidate . '.' . $ip;
        CacheHelper::increment($cacheKey);
    }




}
