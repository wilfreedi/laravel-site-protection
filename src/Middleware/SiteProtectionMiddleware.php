<?php

namespace Wilfreedi\SiteProtection\Middleware;

use Closure;
use Wilfreedi\SiteProtection\Services\BotChecker;
use Wilfreedi\SiteProtection\Services\RateLimiter;

class SiteProtectionMiddleware
{
    protected $botChecker;
    protected $rateLimiter;

    public function __construct(BotChecker $botChecker, RateLimiter $rateLimiter)
    {
        $this->botChecker = $botChecker;
        $this->rateLimiter = $rateLimiter;
    }

    public function handle($request, Closure $next)
    {
        if (config('siteprotection.enabled')) {
            if ($this->botChecker->check($request)) {
                return redirect('/captcha');
            }

            if ($this->rateLimiter->check($request)) {
                return redirect('/captcha');
            }
        }

        return $next($request);
    }
}