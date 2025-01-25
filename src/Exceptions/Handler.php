<?php

namespace Wilfreedi\SiteProtection\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Wilfreedi\SiteProtection\Services\RateLimiterService;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception) {

        if ($exception instanceof NotFoundHttpException) {
            $ip = $request->ip();
            RateLimiterService::increment404Errors($ip);
        }

        return parent::render($request, $exception);
    }
}
