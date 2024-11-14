<?php
/**
 * API Merchant kyc (Known Your Customer)
 * Service ini digunakan untuk mengupload dokumen kyc merchant
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/KYC/Merchant%20kyc
 */

require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/signature.php';

try {
    $kycPath = '/merchant/upload';
    $method = 'POST';
    $imagePath = __DIR__ . '/../../assets/verifikasi.png';

    if (!file_exists($imagePath)) {
        throw new Exception("Image file not found: {$imagePath}");
    }
    // Prepare form data
    $formData = [
        'merchantId' => 'XXXX',
        'type' => 'ktp',
        'no_ktp' => '123456789032XXXX',
        'img' => new CURLFile($imagePath)
    ];
    // Prepare data for signature
    $bodyForSignature = [
        'merchantId' => $formData['merchantId'],
        'type' => $formData['type'],
        'no_ktp' => $formData['no_ktp']
    ];
    $timestamp = dateTime();
    $externalId = generateToken(15);
    $accessToken = $GLOBALS['CONFIG']['TOKEN_B2B'];
    $signature = signatureGeneration(
        $method,
        $kycPath,
        $accessToken,
        $bodyForSignature,
        $timestamp
    );

    // Prepare headers
    $headers = [
        "Authorization: Bearer {$accessToken}",
        "X-PARTNER-ID: {$GLOBALS['CONFIG']['CLIENT_ID']}",
        "X-EXTERNAL-ID: {$externalId}",
        "X-TIMESTAMP: {$timestamp}",
        "X-SIGNATURE: {$signature}",
        "CHANNEL-ID: {$GLOBALS['CONFIG']['CHANNEL_ID']}"
    ];
    // Send request
    $result = postMultipart($kycPath, $formData, $headers);
    // Output result
    echo json_encode($result, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit(1);
}