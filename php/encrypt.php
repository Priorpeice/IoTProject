<?php
function encryptMessage($message, $key) {
    $method = "AES-128-CBC";
    $iv = "0123456789abcdef"; 

    // 메시지 base64로 encode
    $message = base64_encode($message);

    // Zero-padding
    $paddedMessage = $message;
    $pad = 16 - strlen($paddedMessage) % 16;
    if (strlen($paddedMessage) % 16) {
        $paddedMessage = str_pad($paddedMessage, strlen($paddedMessage) + $pad, "\0");
    }

    // encrypt
    $result = openssl_encrypt($paddedMessage, $method, $key, OPENSSL_NO_PADDING, $iv); // OPENSSL_NO_PADDING is important.
    $result = base64_encode($result);

    return $result;
}

function decryptMessage($encryptedData, $key) {
    $method = "AES-128-CBC";
    $iv = "0123456789abcdef"; 

    // Decode the base64-encoded data.
    $encryptedData = base64_decode($encryptedData);

    // Perform decryption:
    $result = openssl_decrypt($encryptedData, $method, $key, OPENSSL_NO_PADDING, $iv);

    // Remove zero-padding.
    $result = rtrim($result, "\0");

    // Decode the message.
    $result = base64_decode($result);

    return $result;
}

