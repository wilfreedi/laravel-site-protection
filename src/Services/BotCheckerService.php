<?php

namespace Wilfreedi\SiteProtection\Services;

class BotCheckerService {

    /*
     * 0 - Все норм
     * 1 - Бот
     * 2 - Бот из белого списка
     */
    public static function check($request, $config): int {
        $userAgent = $request->header('User-Agent');

        if (empty($userAgent)) {
            return 1;
        }

        foreach ($config['bots']['allowed'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return 2;
            }
        }

        if($config['bots']['enabled_all']) {
            if(stripos($userAgent, 'bot') !== false) {
                return 1;
            }
        }

        foreach ($config['bots']['blocked'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return 1;
            }
        }

        return 0;
    }

    public static function validateBot($data) {
        $cookiesEnabled = $data['navigator']['cookiesEnabled'] ?? false;
        $width = $data['screen']['width'] ?? 0;
        $height = $data['screen']['height'] ?? 0;
        $colorDepth = $data['screen']['colorDepth'] ?? 0;
        $isAutomated = $data['isAutomated'] ?? true;

        $isBot = false;

        if(!$cookiesEnabled) {
            $isBot = true;
        }

        if($width <= 0 || $height <= 0 || $colorDepth <= 0) {
            $isBot = true;
        }

        if($isAutomated) {
            $isBot = true;
        }

        return $isBot;
    }

}