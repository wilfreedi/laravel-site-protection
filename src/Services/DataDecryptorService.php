<?php

namespace Wilfreedi\SiteProtection\Services;

class DataDecryptorService
{
    private $encryptionKey;

    public function __construct($encryptionKey) {
        $this->encryptionKey = $encryptionKey;
    }

    public function decryptData($encryptedData, $iv, $tag) {
        $decodedEncryptedData = base64_decode($encryptedData);
        $decodedIv = base64_decode($iv);
        $decodedTag = base64_decode($tag);

        $decryptedData = openssl_decrypt(
            $decodedEncryptedData,
            'aes-128-gcm',
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $decodedIv,
            $decodedTag // Передаем тег аутентификации
        );

        if ($decryptedData === false) {
            return [
                'success' => false,
                'message' => 'Decryption failed'
            ];
        }

        return [
            'success' => true,
            'data' => json_decode($decryptedData, true)
        ];
    }
}