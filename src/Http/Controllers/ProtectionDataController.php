<?php

namespace Wilfreedi\SiteProtection\Http\Controllers;

use Illuminate\Http\Request;
use Wilfreedi\SiteProtection\Services\DataDecryptorService;

class ProtectionDataController
{

    public function data(Request $request) {

        if (!config('siteprotection.js.enabled')) {
            abort(404);
        }
        $key = config('siteprotection.js.key');

        $encrypted = $request->input('encrypted');
        $iv = $request->input('iv');
        $decryptor = new DataDecryptorService($key);
        try {
            $decryptedData = $decryptor->decryptData($encrypted, $iv);
            dd($decryptedData);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
