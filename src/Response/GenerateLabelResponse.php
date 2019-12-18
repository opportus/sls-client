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
 * The generateLabel response.
 *
 * @package Opportus\SlsClient\Response
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
class GenerateLabelResponse implements GenerateLabelResponseInterface
{
    /**
     * @var SimpleXMLElement $envelope
     */
    private $envelope;

    /**
     * @var string $messageId
     */
    private $messageId;

    /**
     * @var null|string $label
     */
    private $label;

    /**
     * @var null|string $parcelNumber
     */
    private $parcelNumber;

    /**
     * Constructs the response.
     *
     * @param string $rawResponse
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
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getParcelNumber()
    {
        return $this->parcelNumber;
    }

    /**
     * Parses the raw response.
     *
     * @param string $rawResponse
     * @throws InvalidArgumentException
     */
    private function parseRawResponse($rawResponse)
    {
        $rawResponseRegexPatterns = $this->getRawResponseRegexPatterns();

        if (1 === \preg_match(\sprintf('~%s~', $rawResponseRegexPatterns[0]), $rawResponse, $data) ||
            1 === \preg_match(\sprintf('~%s~', $rawResponseRegexPatterns[1]), $rawResponse, $data)
        ) {
            $this->envelope  = new SimpleXMLElement($data[0]);
            $this->messageId = $data[1];

            if ('0' === $data[1]) {
                if (\preg_match('~Content-ID: <'.$data[2].'>([\s\S]*?)--uuid~', $rawResponse, $attachments)) {
                    $this->label = $attachments[1];
                }

                $this->parcelNumber = $data[3];
            }


            return;
        }

            echo \substr($rawResponse, 0, 1000);
        throw new InvalidArgumentException(\sprintf(
            '%s expects $rawResponse argument to match the following regex pattern: %s, got %s',
            __METHOD__,
            \implode('|', $rawResponseRegexPatterns),
            $rawResponse
        ));
    }

    /**
     * Gets the raw response regex patterns.
     *
     * @return array
     */
    private function getRawResponseRegexPatterns()
    {
        return \preg_replace('~[\r\n]+|[\s]{2,}~', '', [
            '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                    <ns2:generateLabelResponse xmlns:ns2="http://sls.ws.coliposte.fr">
                        <return>
                            <messages>
                                <id>([a-zA-Z0-9_]+)</id>
                                <messageContent>[À-ÿèêa-zA-Z0-9:\. ]+</messageContent>
                                <type>ERROR</type>
                            </messages>
                        </return>
                    </ns2:generateLabelResponse>
                </soap:Body>
            </soap:Envelope>',

            '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                <soap:Body>
                    <ns2:generateLabelResponse xmlns:ns2="http://sls.ws.coliposte.fr">
                        <return>
                            <messages>
                                <id>(0)</id>
                                <messageContent>La requête a été traitée avec succès</messageContent>
                                <type>INFOS</type>
                            </messages>
                            <labelV2Response>
                                <label>
                                    <xop:Include xmlns:xop="http://www.w3.org/2004/08/xop/include" href="cid:([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}-[0-9a-f]{5}@cxf.apache.org)"/>
                                </label>
                                <parcelNumber>([0-9][A-Z][0-9]{11})</parcelNumber>
                                <parcelNumberPartner>[0-9A-Z]{28}</parcelNumberPartner>
                                (<fields>
                                    (<field>
                                        <key>[A-Z_]+</key>
                                        <value>[. ]*</value>
                                    </field>)*
                                </fields>)*
                            </labelV2Response>
                        </return>
                    </ns2:generateLabelResponse>
                </soap:Body>
            </soap:Envelope>',
        ]);
    }
}

