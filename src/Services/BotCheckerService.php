<?php

namespace Wilfreedi\SiteProtection\Services;

class BotCheckerService {

    public static function check($request, $config): bool {
        $userAgent = $request->header('User-Agent');

        if (empty($userAgent)) {
            return true;
        }

        if($config['bots']['enabled_all']) {
            if(stripos($userAgent, 'bot') !== false) {
                return true;
            }
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

    public static function validateBot($data) {
        $cookiesEnabled = $data['navigator']['cookiesEnabled'];
        $width = $data['screen']['width'];
        $height = $data['screen']['height'];
        $colorDepth = $data['screen']['colorDepth'];
        $isAutomated = $data['isAutomated'];

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