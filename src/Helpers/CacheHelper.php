<?php

namespace Wilfreedi\SiteProtection\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{

    public static function get($key, $default = null) {
        return Cache::get($key, $default);
    }

    public static function put($key, $value, $time = null) {
        Cache::put($key, $value, $time ?? now()->addDay());
    }

    public static function has($key): bool {
        return Cache::has($key);
    }

    public static function forget($key) {
        Cache::forget($key);
    }

    public static function increment($key, $time = null): int {
        $count = 1;
        if (Cache::has($key)) {
            $count = Cache::increment($key);
        } else {
            Cache::put($key, 1, $time ?? now()->addDay());
        }
        return $count;
    }

}