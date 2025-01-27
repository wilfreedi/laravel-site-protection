<?php

use Illuminate\Support\Facades\Route;
use Wilfreedi\SiteProtection\Http\Controllers\CaptchaController;
use Wilfreedi\SiteProtection\Http\Controllers\ProtectionDataController;

Route::get('/site-protection-captcha', [CaptchaController::class, 'show'])->name('site-protection.captcha.show');
Route::post('/site-protection-captcha', [CaptchaController::class, 'verify'])->name('site-protection.captcha.verify');
Route::post('/site-protection-data', [ProtectionDataController::class, 'data'])->name('site-protection.data');