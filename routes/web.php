<?php

use Illuminate\Support\Facades\Route;
use Wilfreedi\SiteProtection\Http\Controllers\CaptchaController;

Route::get('/site-protection-captcha', [CaptchaController::class, 'show'])->name('site-protection.captcha.show');
Route::post('/site-protection-captcha', [CaptchaController::class, 'verify'])->name('site-protection.captcha.verify');