<?php

namespace Wilfreedi\SiteProtection\Middleware;

use Closure;
use Wilfreedi\SiteProtection\Services\SiteProtectionService;

class SiteProtectionMiddleware
{

    public function handle($request, Closure $next) {

        if(SiteProtectionService::isSpam($request)) {
            return to_route('site-protection.captcha.show');
        }

        return $next($request);
    }
}