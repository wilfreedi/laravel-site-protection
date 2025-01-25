<?php

namespace Wilfreedi\SiteProtection\Services\Captcha;

interface CaptchaInterface
{

    public function __construct(string $ip, $token);
    public function captchaCheck(): bool;

}