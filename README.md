A Colissimo Simple Label Solution (SLS) client.

## To do

- Implement cache system
- Implement unit and functional tests
- Implement benchmarks
- Implement all SLS methods
- Implement request validation based on SLS WSDL so that we economize client-server roundtrips

## Installation

### Requirements

- PHP >= 5.4
- libxml PHP extension installed and enabled
- soap PHP extension installed and enabled
- composer

### Step 1 - Download and install the package

Open a command console, enter your project directory and execute:

```sh
$ composer require opportus/sls-client
```

## Guide

A basic code example:

```php
use Opportus\SlsClient\Client;

$client = Client::create();

$requestParameters = [
    'contractNumber' => '111111',
    'password'       => '1111111',
    'outputFormat'   => [
        'x'                  => '0',
        'y'                  => '0',
        'outputPrintingType' => 'PDF_A4_300dpi',
    ],
    'letter' => [
        'service' => [
            'productCode' => 'DOM',
            'depositDate' => '2019-01-01',
        ],
        'parcel' => [
            'weight' => '1',
        ],
        'sender' => [
            'address' => [
                'companyName' => 'FOO',
                'line2'       => '1 RUE FOO',
                'countryCode' => 'FR',
                'city'        => 'FOO',
                'zipCode'     => '00000',
            ],
        ],
        'addressee' => [
            'address' => [
                'companyName' => 'BAR',
                'line2'       => '1 RUE BAR',
                'countryCode' => 'FR',
                'city'        => 'BAR',
                'zipCode'     => '00000',
            ],
        ],
    ],
];

$response = $client->generateLabel($requestParameters);

$response->getMessageId();    // '0'
$response->getLabel();        // <binary attachment>
$response->getParcelNumber(); // '6A11111111111'
```

This library is currently a *passthrough* client of the SLS service. Therefore, the [SLS documentation](https://github.com/opportus/sls-client/blob/master/specs/sls-doc-2019-03.pdf) covers pretty much everything else you need to know in order to use this library.

