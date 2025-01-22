<?php

namespace Wilfreedi\SiteProtection\Services;

class SiteProtectionService
{

    public static function isSpam($request) {

        $config = config('siteprotection');
        $ip = $request->ip();

        if (!$config['enabled']) {
            return false;
        }

        if(self::isIpSafe($ip)) {
            return false;
        }

        if (in_array($request->path(), $config['exclude_paths'])) {
            return false;
        }

        if (BotCheckerService::check($request, $config)) {
            return true;
        }

        if (RateLimiterService::check($request, $config)) {
            return true;
        }

        return false;
    }

    public static function markIpAsSafe(string $ip): void {
        Cache::put("safe_ip:{$ip}", true, now()->addHours(24));
    }

    public static function isIpSafe(string $ip): bool {
        return Cache::has("safe_ip:{$ip}");
    }

}