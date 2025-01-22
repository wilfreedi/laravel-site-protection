<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\SiteProtectionService;

class CaptchaController
{
    public function show()
    {
        $siteKey = config('siteprotection.captcha.site_key');
        return view('siteprotection::captcha', compact('siteKey'));
    }

    public function verify(Request $request)
    {
        $secretKey = config('siteprotection.captcha.secret_key');
        $response = $request->input('g-recaptcha-response');

        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$response}");
        $captchaSuccess = json_decode($verify);

        if ($captchaSuccess->success) {
            $ip = $request->ip();

            SiteProtectionService::markIpAsSafe($ip);

            return redirect('/');
        }

        return back()->withErrors(['captcha' => 'Captcha verification failed.']);
    }
}
