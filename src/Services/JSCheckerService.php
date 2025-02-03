<?php

namespace Wilfreedi\SiteProtection\Services;

use Wilfreedi\SiteProtection\Helpers\CacheHelper;

class JSCheckerService
{

    private static string $keyJSValidate = 'js_validate';

    public static function check($request, $config): int {
        $ip = $request->ip();

        if (self::isJSValidate($ip, $config)) {
            return true;
        }

        return false;
    }

    public static function isJSValidate($ip, $config): bool {
        if($config['js']['enabled']) {
            $cacheKeyJS = self::$keyJSValidate . '.' . $ip;

            $jsValidate = CacheHelper::get($cacheKeyJS, false);
            if (!$jsValidate) {
                $hits = RateLimiterService::getRateLimitAll($ip);

                if ($hits >= 2) {
                    return true;
                }

            }
        }
        return false;
    }

    public static function incrementJSValidate($ip): void {
        $cacheKey = self::$keyJSValidate . '.' . $ip;
        CacheHelper::increment($cacheKey);
    }

}