<?php

require_once 'vendor/autoload.php';

// read from config
$patientId = 'pk-001';
$clientKey = 'c298-4212-92ac-2938';
$apiUrl = 'https://test-bridge.reviewwave.com/v2/';

$interface = new \api\PMSInterface($clientKey, $apiUrl);
$dateNow = date('Y-m-d H:i:s');

try {
    $patientId = $interface->read_patient($patientId);

    echo "Patient ID: $patientId\n";

    if (empty($patientId)) {
        throw new \Exception('Empty patient id');
    }

    $result = $interface->create_appointment($patientId, $dateNow);

    echo '<pre>';
    var_dump($result);
    echo '</pre>';
} catch (\Exception $err) {
    echo 'Error: ' . $err->getMessage();
}
