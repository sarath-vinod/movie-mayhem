<?php
/**
 * SECURED VERSION - encryption.php
 * SECURE CODE: AES-256 encryption example for sensitive data (e.g. movie description)
 * Use a key from environment variable in production; never commit the key.
 */

define('ENC_METHOD', 'aes-256-gcm');
define('ENC_KEY', getenv('MOVIE_MAYHEM_KEY') ?: '32-byte-key-for-demo-only!!'); // 32 bytes for AES-256

function encryptDescription($plaintext) {
    if (empty($plaintext)) return ['ciphertext' => '', 'iv' => '', 'tag' => ''];
    $key = substr(hash('sha256', ENC_KEY, true), 0, 32);
    $iv = random_bytes(16);
    $tag = '';
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag, '', 16);
    return [
        'ciphertext' => base64_encode($ciphertext),
        'iv' => base64_encode($iv),
        'tag' => base64_encode($tag)
    ];
}

function decryptDescription($ciphertextB64, $ivB64, $tagB64) {
    if (empty($ciphertextB64)) return '';
    $key = substr(hash('sha256', ENC_KEY, true), 0, 32);
    $ciphertext = base64_decode($ciphertextB64, true);
    $iv = base64_decode($ivB64, true);
    $tag = base64_decode($tagB64, true);
    if ($ciphertext === false || $iv === false || $tag === false) return '[Decryption error]';
    $plain = openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    return $plain !== false ? $plain : '[Decryption error]';
}
