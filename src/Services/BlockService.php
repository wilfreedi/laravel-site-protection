<?php

namespace Wilfreedi\SiteProtection\Services;

use Illuminate\Support\Facades\Cache;

class BlockService {

    private static string $keyBlackList = 'black_list_ip';
    private static string $keyWhiteList = 'white_list_ip';
    private static string $keyGrayList = 'gray_list_ip';


    public static function addBlackList(string $ip, int $minutes): void {
        Cache::put(self::$keyBlackList . '.' . $ip, true, now()->addMinutes($minutes));
    }

    public static function isBlackList(string $ip): bool {
        return Cache::has(self::$keyBlackList . '.' . $ip);
    }

    public static function addWhiteList(string $ip): void {
        Cache::put(self::$keyWhiteList . '.' . $ip, true, now()->addHours(1));
    }

    public static function isWhiteList(string $ip): bool {
        return Cache::has(self::$keyWhiteList . '.' . $ip);
    }

    public static function addGrayList(string $ip): void {
        Cache::put(self::$keyGrayList . '.' . $ip, true, now()->addHours(1));
    }

    public static function isGrayList(string $ip): bool {
        return Cache::has(self::$keyGrayList . '.' . $ip);
    }

    public static function removeGrayList(string $ip): void {
        Cache::forget(self::$keyGrayList . '.' . $ip);
    }

}