<?php

namespace Wilfreedi\SiteProtection\Services;

class BotCheckerService
{
    public static function check($request, $config): bool {
        $userAgent = $request->header('User-Agent');

        if (empty($userAgent)) {
            return true;
        }

        foreach ($config['bots']['allowed'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return false;
            }
        }

        foreach ($config['bots']['blocked'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

}