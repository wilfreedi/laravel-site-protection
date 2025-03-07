<?php

namespace Wilfreedi\SiteProtection\Middleware;

use Closure;
use Wilfreedi\SiteProtection\Helpers\SessionHelper;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\BotCheckerService;
use Wilfreedi\SiteProtection\Services\JSCheckerService;
use Wilfreedi\SiteProtection\Services\RateLimiterService;

class SiteProtectionMiddleware
{

    public function handle($request, Closure $next) {

        $routeRedirect = to_route('site-protection.captcha.show');

        $config = config('siteprotection');
        $ip = $request->ip();

        if (!$config['enabled']) {
            return $next($request);
        }

        RateLimiterService::incrementRateLimitAll($ip);

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
        $checkBot = BotCheckerService::check($request, $config);
        if($checkBot == 1) {
            SessionHelper::setBeforeLink($url);
            BlockService::addGrayList($ip);
            return $routeRedirect;
        } else if($checkBot == 2) {
            return $next($request);
        }

        if (RateLimiterService::check($request, $config)) {
            SessionHelper::setBeforeLink($url);
            BlockService::addGrayList($ip);
            return $routeRedirect;
        }

        if(JSCheckerService::check($request, $config)) {
            SessionHelper::setBeforeLink($url);
            BlockService::addGrayList($ip);
            return $routeRedirect;
        }

        return $next($request);
    }
}