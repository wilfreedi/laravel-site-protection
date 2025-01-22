<?php

namespace Wilfreedi\SiteProtection\Services;

class BotChecker extends BaseChecker
{
    public function check($request): bool
    {
        $userAgent = $request->header('User-Agent');

        if (empty($userAgent)) {
            $this->log('Empty User-Agent detected', ['ip' => $request->ip()]);
            return true;
        }

        foreach ($this->config['bots']['allowed'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return false;
            }
        }

        foreach ($this->config['bots']['blocked'] as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                $this->log('Blocked bot detected', ['ip' => $request->ip()]);
                return true;
            }
        }

        return false;
    }
}