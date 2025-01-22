<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController
{
    public function show()
    {
        return view('siteprotection::captcha');
    }

    public function verify(Request $request)
    {
        // Валидация CAPTCHA
        // Если успешно, отметьте IP как безопасный
        return redirect('/');
    }
}
