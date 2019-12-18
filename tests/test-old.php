<?php

use Opportus\SlsClient\Client;

require('../vendor/autoload.php');

$client = Client::create();

$parameters = [
    'contractNumber' => '000000',
    'password' => 'AAAAAAAA',
    'outputFormat' => [
        'x' => '0',
        'y' => '0',
        'outputPrintingType' => 'PDF_10x15_300dpi',
    ],
    'letter' => [
        'service' => [
            'productCode' => 'DOM',
            'depositDate' => '2101-01-01',
        ],
        'parcel' => [
            'weight' => '1',
        ],
        'sender' => [
            'address' => [
                'companyName' => 'FOO',
                'line2' => '1 rue exemple',
                'countryCode' => 'FR',
                'city' => 'PARIS',
                'zipCode' => '75000',
            ],
        ],
        'addressee' => [
            'address' => [
                'companyName' => 'BAR',
                'line2' => '1 rue exemple',
                'countryCode' => 'FR',
                'city' => 'PARIS',
                'zipCode' => '75000',
            ],
        ],
    ],
];

$response = $client->generateLabel($parameters);

if ('30000' !== $response->getMessageId()) {
    return 1;
}

return 0;

