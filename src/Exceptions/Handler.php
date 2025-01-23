<?php

namespace Wilfreedi\SiteProtection\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception) {

        if ($exception instanceof NotFoundHttpException) {

        }

        return parent::render($request, $exception);
    }
}
