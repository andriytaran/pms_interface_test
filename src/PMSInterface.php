<?php

namespace api;

use GuzzleHttp\Client;

class PMSInterface
{
    protected $clientKey;
    protected $guzzleClient;

    public function __construct($clientKey, $apiUrl)
    {
        $this->clientKey = $clientKey;

        $this->guzzleClient = new Client(['base_uri' => $apiUrl]);
    }

    public function create_appointment($patient_id, $appointment_datetime)
    {
        // $appointmentKey = '1234' . md5(rand(99, 10000)); // random 36 digits string
        $appointmentKey = uniqid();

        try {
            $response = $this->guzzleClient->post(
                'appointment',
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'clientKey' => $this->clientKey,
                        'appointments' => [
                            [
                                'patientId' => $patient_id,
                                'appointmentKey' => $appointmentKey,
                                'appointmentDate' => $appointment_datetime
                            ]
                        ]
                    ]
                ]
            );

            $result = json_decode($response->getBody()->getContents(), true);

            return $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
            $response = $ex->getResponse();
            $jsonBody = (string) $response->getBody();

            throw new \Exception('Bad request: ' . $jsonBody);
        }
    }

    public function read_patient($patient_key)
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                'patient',
                [
                    'query' => [
                        'clientKey' => $this->clientKey,
                        'patientKey' => $patient_key
                    ]
                ]
            );

            $result = json_decode($response->getBody()->getContents(), true);

            return (int) $result;
        } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
            $response = $ex->getResponse();
            $jsonBody = (string) $response->getBody();

            throw new \Exception('Bad request: ' . $jsonBody);
        }
    }
}
