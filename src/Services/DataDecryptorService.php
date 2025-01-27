<?php

namespace Wilfreedi\SiteProtection\Services;

class DataDecryptorService
{
    private $encryptionKey;

    public function __construct($encryptionKey) {
        $this->encryptionKey = $encryptionKey;
    }

    public function decryptData($encryptedData, $iv) {
        $decodedEncryptedData = base64_decode($encryptedData);
        $decodedIv = base64_decode($iv);

        $decryptedData = openssl_decrypt(
            $decodedEncryptedData,
            'aes-128-gcm',
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $decodedIv,
            $tag = null
        );

        if ($decryptedData === false) {
            throw new \Exception("Decryption failed");
        }

        return json_decode($decryptedData, true);
    }
}