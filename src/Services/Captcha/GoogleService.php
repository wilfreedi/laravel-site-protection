<?php

namespace Wilfreedi\SiteProtection\Services\Captcha;

use GuzzleHttp\Client;

class GoogleService {

    private string $url = 'https://www.google.com/recaptcha/api/siteverify';
    private string $secret;
    private string $token;
    private string $action;
    private string $ip;

    public function __construct(string $ip, $token, $action = null) {
        $this->secret = config('siteprotection.captcha.providers.recaptcha.secret_key');
        $this->token = $token;
        $this->action = $action;
        $this->ip = $ip;
    }

    public function captchaCheck(): bool{
        $data = $this->request();
        if ($data['success']) {
            return true;
        }
        return false;
    }
    public function captchaCheckV3(string $token, string $action): bool {
        $response = $this->request();
        if ($response && $response['success'] && $response['action'] == $this->action && $response['score'] > 0.7) {
            return true;
        }
        return false;
    }

    public function request(): array {
        $client = new Client();
        $data = [
            'secret'   => $this->secret,
            'response' => $this->token,
            'remoteip' => $this->ip
        ];
        $response = $client->request('POST', $this->url, [
            'body'      => json_encode($data),
            'timeout'   => 20
        ]);
        return json_decode($response->getBody(), true);
    }

}