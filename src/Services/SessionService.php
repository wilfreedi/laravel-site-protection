<?php

namespace Wilfreedi\SiteProtection\Services;

class SessionService {

    private static string $key = 'siteprotection';
    private static string $keyBeforeLink = 'before_link';

    public static function setBeforeLink(string $link): void {
        session()->put(self::$key . '.' . self::$keyBeforeLink, $link);
    }

    public static function getBeforeLink(): ?string {
        return session()->get(self::$key . '.' . self::$keyBeforeLink, '/');
    }

    public static function removeBeforeLink(): void {
        session()->forget(self::$key . '.' . self::$keyBeforeLink);
    }

}