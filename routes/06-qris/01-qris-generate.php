<?php
/**
 * API Qris Generate
 * Service ini digunakan untuk membuat QRIS dengan metode Merchant Presented Mode (MPM). Response berupa URL Image QRIS dan QR Content
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/QRIS/Qris%20Generate
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];

require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../auth/signature.php';


$path = '/qr/qr-mpm-generate';
$httpMethod = 'POST';

// body
$body = [
    'terminalId' => 'A01',
    'amount' => [
        'value' => '9000.00',
        'currency' => 'IDR'
    ],
    'feeAmount' => [
        'value' => '1000.00',
        'currency' => 'IDR'
    ],
    'merchantId' => "XXXX",
    'validityPeriod' => "2024-10-18T14:49:25+07:00", 
    'additionalInfo' => [
        'feeType' => "1", 
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

// handle logic
if ($response) {
    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Failed to get a response.\n";
}
