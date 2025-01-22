<?php

namespace Wilfreedi\SiteProtection\Services;

use Illuminate\Support\Facades\Cache;

class BlockService
{

    public static function addBlackList(string $ip, int $minutes): void {
        Cache::put("black_list_ip:{$ip}", true, now()->addMinutes($minutes));
    }

    public static function isBlackList(string $ip): bool {
        return Cache::has("black_list_ip:{$ip}");
    }

    public static function addWhiteList(string $ip): void {
        Cache::put("white_list_ip:{$ip}", true, now()->addHours(1));
    }

    public static function isWhiteList(string $ip): bool {
        return Cache::has("white_list_ip:{$ip}");
    }

}