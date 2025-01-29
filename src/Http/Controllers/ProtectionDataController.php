<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\DataDecryptorService;

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

        $encryptedData = $request->input('data');

        // 1. Декодируем Base64
        $jsonString = base64_decode($encryptedData);

        // 2. Преобразуем JSON в массив
        $data = json_decode($jsonString, true);

        if (!isset($data['mix'], $data['data'], $data['message'])) {
            $response['message'] = 'Invalid data format';
            return response()->json($response, 400);
        }

        // 4. Декодируем отдельные части
        $decodedIv = $data['mix'];
        $decodedEncryptedData = $data['data'];
        $decodedTag = $data['message'];

        $decryptor = new DataDecryptorService($key);
        try {
            $decryptedData = $decryptor->decryptData($decodedEncryptedData, $decodedIv, $decodedTag);

            // TODO: Нужно сделать проверки на бота

            return response()->json([
                'success' => true,
                'data'    => $decryptedData
            ], 200);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            return response()->json($response, 400);
        }
        return response()->json($response, 400);
    }
}
