<?php

namespace Wilfreedi\SiteProtection\Middleware;

use Closure;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\BotCheckerService;
use Wilfreedi\SiteProtection\Services\RateLimiterService;
use Wilfreedi\SiteProtection\Services\SessionService;

class SiteProtectionMiddleware
{

    public function handle($request, Closure $next) {

        $routeRedirect = to_route('site-protection.redirect');

        $config = config('siteprotection');
        $ip = $request->ip();

        if (!$config['enabled']) {
            return $next($request);
        }

        if(BlockService::isBlackList($ip)) {
            abort(403);
        }

        if(BlockService::isGrayList($ip)) {
            return $routeRedirect;
        }

        if(BlockService::isWhiteList($ip)) {
            return $next($request);
        }

        $path = $request->path();

        $config['exclude_paths'][] = 'site-protection-captcha';
        if (in_array($path, $config['exclude_paths'])) {
            return $next($request);
        }

        $url = $request->fullUrl();
        if (BotCheckerService::check($request, $config)) {
            SessionService::setBeforeLink($url);
            BlockService::addGrayList($ip);
            return $routeRedirect;
        }

        if (RateLimiterService::check($request, $config)) {
            SessionService::setBeforeLink($url);
            BlockService::addGrayList($ip);
            return $routeRedirect;
        }

        return $next($request);
    }
}