<?php
namespace LazarusPhp\DatabaseManager\Traits;

trait Encryption
{
    private static $key = "b157faed8b8974d55ae3af5ea733cd55e2e1138cbf7020048eb0ebe9234ac4702";
    private static $cipher = 'AES-256-CBC';

    public static function encryptValue($value)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher));
        $encrypted = openssl_encrypt($value, self::$cipher, self::$key, 0, $iv);
    
        // Combine IV and encrypted value for decryption later
        return base64_encode($iv . $encrypted);
    }
    
    public static function decryptValue($value)
    {
        $decoded = base64_decode($value);
    
        // Extract IV and encrypted data
        $iv_length = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($decoded, 0, $iv_length);
        $encrypted = substr($decoded, $iv_length);
    
        return openssl_decrypt($encrypted, self::$cipher, self::$key, 0, $iv);
    }
}