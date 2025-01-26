<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\Captcha\GoogleService;
use Wilfreedi\SiteProtection\Services\Captcha\YandexService;
use Wilfreedi\SiteProtection\Services\SessionService;

class CaptchaController
{
    public function show() {

        if (!config('siteprotection.enabled')) {
            return redirect('/');
        }

        $theme = config('siteprotection.theme_color');
        $provider = config('siteprotection.captcha.provider');
        $siteKey = config("siteprotection.captcha.providers.$provider.site_key");

        if(!$siteKey) {
            abort(404);
        }

        return view('siteprotection::captcha', [
            'siteKey'  => $siteKey,
            'provider' => $provider,
            'theme'    => $theme
        ]);
    }

    public function verify(Request $request) {

        if (!config('siteprotection.enabled')) {
            return redirect('/');
        }
        $provider = config('siteprotection.captcha.provider');
        $siteKey = config("siteprotection.captcha.providers.$provider.secret_key");
        if(!$siteKey) {
            abort(404);
        }

        $ip = $request->ip();

        $message = 'Captcha verification failed';
        if($provider == 'recaptcha') {
            $token = $request->input('g-recaptcha-response');
            $google = new GoogleService($ip, $token);
            if(!$google->captchaCheck()) {
                return back()->with(['error' => $message]);
            }
        } else if($provider == 'yandex') {
            $token = $request->input('token');
            $yandex = new YandexService($ip, $token);
            if(!$yandex->captchaCheck()) {
                return back()->with(['error' => $message]);
            }
        } else {
            abort(404);
        }

        BlockService::addWhiteList($ip);
        BlockService::removeGrayList($ip);

        $url = SessionService::getBeforeLink();
        SessionService::removeBeforeLink();

        return redirect($url);
    }
}
