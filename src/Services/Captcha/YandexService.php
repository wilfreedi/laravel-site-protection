<?php

namespace Wilfreedi\SiteProtection\Services\Captcha;

use GuzzleHttp\Client;

class YandexService {

    private string $url = 'https://smartcaptcha.yandexcloud.net/validate';
    private string $secret;
    private string $token;
    private string $ip;

    public function __construct(string $ip, $token) {
        $this->secret = config('siteprotection.captcha.providers.yandex.secret_key');
        $this->token = $token;
        $this->ip = $ip;
    }

    public function captchaCheck(): bool{
        $data = $this->request();
        if ($data['status'] == 'ok') {
            return true;
        }
        return false;
    }

    public function request(): array {
        $client = new Client();
        $data = [
            'secret'   => $this->secret,
            'token'    => $this->token,
            'ip'       => $this->ip
        ];
        $response = $client->request('POST', $this->url, [
            'body'      => json_encode($data),
            'timeout'   => 20
        ]);
        return json_decode($response->getBody(), true);
    }

}