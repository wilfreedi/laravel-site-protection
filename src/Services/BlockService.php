<?php

namespace Wilfreedi\SiteProtection\Services;

class BlockService
{

    public static function blocked(string $ip): void {
        Cache::put("dangerous_ip:{$ip}", true, now()->addHours(24));
        Cache::put("blocked_ip:{$ip}", true, now()->addMinutes($config['404_protection']['block_time_minutes']));
    }

    public static function isBlocked(string $ip): bool {
        return Cache::has("dangerous_ip:{$ip}");
    }

}