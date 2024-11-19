<?php
/**
 * API Qris query
 * Service ini digunakan untuk cek status pembayaran dari QRIS
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/QRIS/Qris%20Query
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];

require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../auth/signature.php';


$path = '/qr/qr-mpm-query';
$httpMethod = 'POST';

// body
$body = [
    "merchantId" => "121xxxx",
    "serviceCode" => "47",
    "additionalInfo"  => [
        "qrId"  => "19783090"
    ]
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

// Handle the response
if ($response) {
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Failed to get a response.\n";
}
