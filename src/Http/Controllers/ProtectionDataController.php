<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\BlockService;
use Wilfreedi\SiteProtection\Services\BotCheckerService;
use Wilfreedi\SiteProtection\Services\DataDecryptorService;
use Wilfreedi\SiteProtection\Services\RateLimiterService;

class ProtectionDataController
{

    public function data(Request $request) {
        $response = [
            'success' => false,
            'message' => 'Server error',
        ];

        if (!config('siteprotection.js.enabled')) {
            abort(404);
        }
        $key = config('siteprotection.js.key');

        $ip = $request->ip();
        $encryptedData = $request->input('data');

        $jsonString = base64_decode($encryptedData);

        $data = json_decode($jsonString, true);

        if (!isset($data['mix'], $data['data'], $data['message'])) {
            $response['message'] = 'Invalid data format';
            return response()->json($response, 400);
        }

        $decodedIv = $data['mix'];
        $decodedEncryptedData = $data['data'];
        $decodedTag = $data['message'];

        $decryptor = new DataDecryptorService($key);
        $decryptedData = $decryptor->decryptData($decodedEncryptedData, $decodedIv, $decodedTag);

        if($decryptedData['success']) {
            $isBot = BotCheckerService::validateBot($decryptedData['data']);
            if($isBot) {
                BlockService::addGrayList($ip);
            } else {
                RateLimiterService::incrementJSValidate($ip);
            }
            return response()->json([
                                        'success' => true,
                                        'bot'     => $isBot
                                    ], 200);
        } else {
            BlockService::addGrayList($ip);
            $response['message'] = 'Decryption failed';
            return response()->json($response, 400);
        }
    }
}
