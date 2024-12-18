<?php
/**
 * API Merchant Register
 * Berfungsi Service ini digunakan untuk melakukan proses registrasi merchant 
 * 
 * Referensi:
 * API Documentation: https://qris-mpm-docs.speedcash.co.id/docs/payments/qris-mpm/Registrasi/Merchant%20Register
 */

require_once __DIR__ . '/../../config/config.php';
$config = $GLOBALS['CONFIG'];

require_once __DIR__ . '/../../utils/utils.php';
require_once __DIR__ . '/../../service/sender.php';
require_once __DIR__ . '/../../auth/signature.php';


$path = '/merchant/registration';
$httpMethod = 'POST';

// body
$body = [
    "nama_pemilik" =>  "rosidi dragon",
    "nama_outlet" => "rosididragon store",
    "notelp_pemilik" => "6281200xxxxx",
    "notelp_outlet" => "6281200xxxxx",
    "email_pemilik" => "rosididragon@gmail.com",
    "email_outlet" => "rosididragon@gmail.com",
    "nik" => "292828182832xxxx",
    "kewarganegaraan" =>  "ID",
    "id_provinsi_pemilik" => "28",
    "id_kota_pemilik" => "253",
    "id_kecamatan_pemilik" =>  "2487",
    "id_kelurahan_pemilik" =>  "31014",
    "kode_pos_pemilik" => "09645",
    "alamat_pemilik" => "Jl. Mayjend Prof. Dr. Moestopo No. 6, Surabaya, Jawa Timur", 
    "id_provinsi_outlet" => "28",
    "id_kota_outlet" => "253",
    "id_kecamatan_outlet" =>  "2487",
    "id_kelurahan_outlet" =>  "31014",
    "alamat_outlet" => "Jl. Prof. Moh. Hasan Simpang Surabaya",
    "kode_pos_outlet" => "11170",
    "type_merchant" => "0",
    "kriteria" => "UKE",
    "mcc" => "5812",
    "npwp" => "0",
    "is_onlineshop" =>  0
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
