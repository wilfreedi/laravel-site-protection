<?php

namespace Wilfreedi\SiteProtection\Services;

use Wilfreedi\SiteProtection\Helpers\CacheHelper;

class BlockService {

    private static string $keyBlackList = 'black_list_ip';
    private static string $keyWhiteList = 'white_list_ip';
    private static string $keyGrayList = 'gray_list_ip';


    public static function addBlackList(string $ip, int $seconds): void {
        CacheHelper::put(self::$keyBlackList . '.' . $ip, true, now()->addSeconds($seconds));
    }

    public static function isBlackList(string $ip): bool {
        return CacheHelper::has(self::$keyBlackList . '.' . $ip);
    }

    public static function addWhiteList(string $ip): void {
        CacheHelper::put(self::$keyWhiteList . '.' . $ip, true, now()->addHours(1));
    }

    public static function isWhiteList(string $ip): bool {
        return CacheHelper::has(self::$keyWhiteList . '.' . $ip);
    }

    public static function addGrayList(string $ip): void {
        CacheHelper::put(self::$keyGrayList . '.' . $ip, true, now()->addHours(1));
    }

    public static function isGrayList(string $ip): bool {
        return CacheHelper::has(self::$keyGrayList . '.' . $ip);
    }

    public static function removeGrayList(string $ip): void {
        CacheHelper::forget(self::$keyGrayList . '.' . $ip);
    }

}