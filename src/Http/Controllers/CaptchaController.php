<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\SiteProtectionService;

class CaptchaController
{
    public function show() {

        if (!config('siteprotection.captcha.enabled')) {
            return redirect('/');
        }

        $provider = config('siteprotection.captcha.provider');
        $siteKey = config("siteprotection.captcha.providers.$provider.site_key");
        return view('siteprotection::captcha', [
            'siteKey'  => $siteKey,
            'provider' => $provider
        ]);
    }

    public function verify(Request $request) {

        if (!config('siteprotection.captcha.enabled')) {
            return redirect('/');
        }
        $provider = config('siteprotection.captcha.provider');
        $secretKey = config("siteprotection.captcha.providers.$provider.secret_key");

        $response = $request->input('g-recaptcha-response');

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$response}");
        $captchaSuccess = json_decode($verify);

        if ($captchaSuccess->success) {
            $ip = $request->ip();

            BlockService::addWhiteList($ip);

            return redirect('/');
        }

        return back()->with(['error' => 'Captcha verification failed.']);
    }
}
