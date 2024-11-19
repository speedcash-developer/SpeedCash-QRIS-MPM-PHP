<?php
/**
 * API Send QR Notify
 * Service ini digunakan untuk menerima callback dari SpeedCash
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/QRIS/Qris%20Notify
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];
require_once __DIR__ . '/../../auth/signature.php';
require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';

// Sender function
function send() {
    global $CONFIG;
    $body = [
        'originalReferenceNo' => '4124213214',
        'originalPartnerReferenceNo' => '21421412321',
        'latestTransactionStatus' => '00',
        'amount' => [
            'value' => '15000.00',
            'currency' => 'IDR'
        ],
        'additionalInfo' => [
            'nmid' => 'ID321412xxxx',
            'terminalId' => 'A01',
            'qrisId' => '10099768',
            'issuerReff' => '21412XXXX',
            'buyyerReff' => 'Rosidi Dragons',
            'brandName' => 'GOPAY',
            'transactionDate' => '2024-10-31T11:00:32+07:00',
            'rrn' => '00000530XXXX',
            'feeAmount' => '2000.00',
            'mdr' => '107.8000000000',
            'feeAdmin' => '0',
            'typeQr' => 'dynamic',
            'description' => 'Pembayaran makan siang',
            'merchantId' => '121xxxx',
            'issuerId' => '9360xxxx',
            'acquirerId' => '9360xxxx'
        ]
    ];

    // Endpoint configuration
    $HTTPMethod = 'POST';
    $EndpointUrl = '/v1.0/qr/qr-mpm-notify';
    $Timestamp = dateTime(); 
    $ExternalId = generateToken(16);

    $signature = signatureRsaGeneration($HTTPMethod, $EndpointUrl, $body, $Timestamp);

    // Prepare headers
    $headers = [
        'Content-Type: application/json',
        'X-TIMESTAMP: ' . $Timestamp,
        'X-PARTNER-ID: ' . $CONFIG['CLIENT_ID'],
        'X-EXTERNAL-ID: ' . $ExternalId,
        'X-SIGNATURE: ' . $signature
    ];

    try {
        $response = postCallback($EndpointUrl, $body, $headers);
        echo "\nResponse received: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";
        return $response;
    } catch (Exception $error) {
        echo "\nError sending: " . $error->getMessage() . "\n";
        throw $error;
    }
}

if (php_sapi_name() === 'cli') {
    echo "Starting sender...\n";
    echo "----------------------------------------\n";
    send();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/v1.0/qr/qr-mpm-notify') {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $body = file_get_contents('php://input');
        $bodyArray = json_decode($body, true);

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        
        // Convert header names
        $timestamp = $_SERVER['HTTP_X_TIMESTAMP'] ?? '';
        $signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';    
        $isValid = signatureRsaValidation($method, $url, $bodyArray, $timestamp, $signature);
        
        header('Content-Type: application/json');
        
        if ($isValid) {
            $response = [
                'responseCode' => '2005200',
                'responseMessage' => 'success'
            ];
            echo json_encode($response);
        } else {
            http_response_code(400);
            $response = [
                'status' => 'invalid signature',
                'signature' => $signature,
                'timestamp' => $timestamp,
                'headers' => $headers
                ];
            
            echo json_encode($response);
        }
        exit;
    }
}
