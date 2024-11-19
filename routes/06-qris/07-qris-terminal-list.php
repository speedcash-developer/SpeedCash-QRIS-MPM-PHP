<?php
/**
 * API Qris Generate
 * Service ini di gunakan untuk melihat terminalId yang telah di generate merchant
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/QRIS/Qris%20Terminal%20List
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];

require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../auth/signature.php';


$path = '/qr/qr-terminal-list';
$httpMethod = 'POST';

// body
$body = [
    'merchantId' => "121xxxx",
];

// Headers
$clientId = $config['CLIENT_ID'];
$accessToken = $config['TOKEN_B2B'];
$externalId = generateToken(15);
$timeStamp = dateTime();
$channelId = $config['CHANNEL_ID'];
$signature = signatureGeneration($httpMethod, $path, $accessToken, json_encode($body), $timeStamp);

$headers = [
    'Authorization: Bearer ' . $accessToken,
    'X-PARTNER-ID: ' . $clientId,
    'X-EXTERNAL-ID: ' . $externalId,
    'X-TIMESTAMP: ' . $timeStamp,
    'X-SIGNATURE: ' . $signature,
    'CHANNEL-ID: ' . $channelId,
    'Content-Type: application/json'
];

// Send the POST request
$response = post($path, $body, $headers);

// handle logic
if ($response) {
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Failed to get a response.\n";
}