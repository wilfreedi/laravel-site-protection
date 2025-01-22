<?php

namespace Wilfreedi\SiteProtection\Middleware;

use Closure;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\BotCheckerService;
use Wilfreedi\SiteProtection\Services\RateLimiterService;
use Wilfreedi\SiteProtection\Services\SiteProtectionService;

class SiteProtectionMiddleware
{

    public function handle($request, Closure $next) {

        $config = config('siteprotection');
        $ip = $request->ip();

        if (!$config['enabled']) {
            return $next($request);
        }

        if(BlockService::isBlackList($ip)) {
            abort(403);
        }

        if(BlockService::isWhiteList($ip)) {
            return $next($request);
        }

        if (in_array($request->path(), $config['exclude_paths'])) {
            return $next($request);
        }

        if (BotCheckerService::check($request, $config)) {
            return to_route('site-protection.captcha.show');
        }

        if (RateLimiterService::check($request, $config)) {
            return to_route('site-protection.captcha.show');
        }

        return $next($request);
    }
}