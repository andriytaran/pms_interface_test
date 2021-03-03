<?php

require_once 'vendor/autoload.php';

// read from config
$patientId = 'pk-001';
$clientKey = 'c298-4212-92ac-2938';
$apiUrl = 'https://test-bridge.reviewwave.com/v2/';

$interface = new \api\PMSInterface($clientKey, $apiUrl);
$dateNow = date('Y-m-d H:i:s');

try {
    $patients = $interface->read_patient($patientId);

    if ($patients['status'] != 200) {
        throw new \Exception('Error while loading patients');
    }

    $result = $interface->create_appointment($patients['patients'][0]['patientID'], $dateNow);

    echo '<pre>';
    var_dump($result);
    echo '</pre>';
} catch (\Exception $err) {
    echo 'Error: ' . $err->getMessage();
}
