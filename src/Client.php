<?php

/**
 * This file is part of the opportus/sls-client package.
 *
 * Copyright (c) 2019-2020 Clément Cazaud <clement.cazaud@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Opportus\SlsClient;

use Opportus\SlsClient\Exception\InvalidArgumentException;
use Opportus\SlsClient\Response\CheckGenerateLabelResponseInterface;
use Opportus\SlsClient\Response\GenerateLabelResponseInterface;
use Opportus\SlsClient\Response\ResponseInterface;
use Opportus\SlsClient\Response\FaultResponse;
use SoapClient;

/**
 * The client.
 *
 * @package Opportus\SlsClient
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
class Client implements ClientInterface
{
    const SERVICE_VERSION = '2.0';
    const SERVICE_URI     = 'https://ws.colissimo.fr/sls-ws/SlsServiceWS/'.self::SERVICE_VERSION;
    const SERVICE_WSDL    = self::SERVICE_URI.'?wsdl';

    /**
     * @var SoapClient $soapClient
     */
    private $soapClient;

    /**
     * Constructs the client.
     *
     * @param SoapClient $soapClient
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    /**
     * {@inheritdoc}
     */
    public static function create()
    {
        $soapClient = new SoapClient(self::SERVICE_WSDL, [
            'exceptions' => false,
            'trace'      => true,
        ]);

        return new self($soapClient);
    }

    /**
     * {@inheritdoc}
     */
    public function generateLabel($parameters)
    {
        return $this->request(__FUNCTION__, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function checkGenerateLabel($parameters)
    {
        return $this->request(__FUNCTION__, $parameters);
    }

    /**
     * Requests the WSL service.
     *
     * @param string $function
     * @param array $parameters
     * @return ResponseInterface
     * @throws InvalidOperationException
     */
    private function request($function, $parameters)
    {
        $requestType  = \sprintf('Opportus\SlsClient\Request\%sRequest',   \ucfirst($function));
        $responseType = \sprintf('Opportus\SlsClient\Response\%sResponse', \ucfirst($function));

        $request = new $requestType($parameters);

        $rawResponse = $this->soapClient->__doRequest(
            $request,
            self::SERVICE_URI,
            $function,
            self::SERVICE_VERSION
        );

        try {
            $response = new $responseType($rawResponse);
        } catch (InvalidArgumentException $exception) {
            try {
                $response = new FaultResponse($rawResponse);
            } catch (InvalidArgumentException $exception) {
                throw new Exception(\sprintf(
                    'No supported response type matches the following raw response: %s',
                    $rawResponse
                ));
            }

            throw new InvalidArgumentException(\sprintf(
                '%s: %s',
                $response->getCode(),
                $response->getString()
            ));
        }

        return $response;
    }
}

