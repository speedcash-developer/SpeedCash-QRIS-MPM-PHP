<?php
/**
 * API History Transaction
 * Service ini digunakan mendapatkan data dari transaksi QRIS yang telah di buat
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/Histori%20Transaksi/transkasi%20history%20list
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];

require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../auth/signature.php';


$path = '/transaction-history-list';
$httpMethod = 'POST';

// body
$body = [
    'fromDateTime' => '2024-10-25T00:00:00+07:00',
    'toDateTime' => "2024-10-29T00:00:00+07:00",
    'pageSize' => "2024-10-18T14:49:25+07:00", 
    'pageNumber' => "0", 
    'additionalInfo' => [
        'merchantId' => "121xxxx", 
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
