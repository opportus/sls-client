<?php

/**
 * This file is part of the opportus/sls-client package.
 *
 * Copyright (c) 2019-2020 Clément Cazaud <clement.cazaud@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Opportus\SlsClient\Response;

use Opportus\SlsClient\Exception\InvalidArgumentException;
use SimpleXMLElement;

/**
 * The fault response.
 *
 * @package Opportus\SlsClient\Response
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
class FaultResponse implements FaultResponseInterface
{
    /**
     * @var SimpleXMLElement $envelope
     */
    private $envelope;

    /**
     * @var string $code
     */
    private $code;

    /**
     * @var string $string
     */
    private $string;

    /**
     * Constructs the response.
     *
     * @param string $rawResponse
     * @throws InvalidArgumentException
     */
    public function __construct($rawResponse)
    {
        if (false === \is_string($rawResponse)) {
            throw new InvalidArgumentException(\sprintf(
                '%s expects $rawResponse argument to be of type string, %s type given.',
                __METHOD__,
                \gettype($parameters)
            ));
        }

        $this->parseRawResponse($rawResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->envelope->asXml();
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * Parses the raw response.
     *
     * @param string $rawResponse
     * @throws InvalidArgumentException
     */
    private function parseRawResponse($rawResponse)
    {
        $rawResponseRegexPattern = $this->getRawResponseRegexPattern();

        if (0 === \preg_match(\sprintf('~%s~', $rawResponseRegexPattern), $rawResponse, $matches)) {
            throw new InvalidArgumentException(\sprintf(
                '%s expects $rawResponse argument to match the following regex pattern: %s, got %s',
                __METHOD__,
                $rawResponseRegexPattern,
                $rawResponse
            ));
        }

        $this->envelope = new SimpleXMLElement($matches[0]);
        $this->code     = $matches[1];
        $this->string   = $matches[2];
    }

    /**
     * Gets the raw response regex pattern.
     *
     * @return string
     */
    private function getRawResponseRegexPattern()
    {
        return \preg_replace('~[\r\n]+|[\s]{2,}~', '',
            '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                    <soap:Fault>
                        <faultcode>(soap:Client)</faultcode>
                        <faultstring>([À-ÿa-zA-Z0-9\.:\-{}\' ]+)</faultstring>
                    </soap:Fault>
                </soap:Body>
            </soap:Envelope>'
        );
    }
}

