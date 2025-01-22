<?php

namespace Wilfreedi\SiteProtection\Services;

abstract class BaseChecker
{
    protected $config;

    public function __construct()
    {
        $this->config = config('siteprotection');
    }

    abstract public function check($request): bool;

    protected function log(string $message, array $context = [])
    {
        if ($this->config['logging']['enabled']) {
            \Log::channel($this->config['logging']['driver'])->warning($message, $context);
        }
    }
}
