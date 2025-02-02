<?php

namespace Wilfreedi\SiteProtection\Services;

class BotCheckerService {

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

        if($config['bots']['enabled_all']) {
            if(stripos($userAgent, 'bot') !== false) {
                return true;
            }
        }

        foreach ($config['bots']['blocked'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
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