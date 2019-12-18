<?php

/**
 * This file is part of the opportus/sls-client package.
 *
 * Copyright (c) 2019-2020 Clément Cazaud <clement.cazaud@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Opportus\SlsClient\Request;

use Opportus\SlsClient\Exception\InvalidArgumentException;
use SimpleXMLElement;

/**
 * The checkGenerateLabel request.
 *
 * @package Opportus\SlsClient\Request
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
class CheckGenerateLabelRequest implements CheckGenerateLabelRequestInterface
{
    /**
     * @var SimpleXMLElement $envelope
     */
    private $envelope;

    /**
     * Constructs the request.
     *
     * @param array $parameters
     * @throws InvalidArgumentException
     */
    public function __construct(array $parameters)
    {
        if (false === \is_array($parameters)) {
            throw new InvalidArgumentException(\sprintf(
                '%s expects $parameters argument to be of type array, %s type given.',
                __METHOD__,
                \gettype($parameters)
            ));
        }

        $this->buildEnvelope($parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->envelope->asXml();
    }

    /**
     * Builds the envelope.
     *
     * @param array $parameters
     */
    private function buildEnvelope(array $parameters)
    {
        $this->envelope = new SimpleXMLElement('<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" />');
        $this->envelope->addChild('soapenv:Header');
        $node = $this->envelope->addChild('soapenv:Body');
        $node = $node->addChild('sls:generateLabel', null, 'http://sls.ws.coliposte.fr');
        $parametersParentNode = $node->addChild('generateLabelRequest', null, '');

        $this->addParametersInEnvelope($parameters, $parametersParentNode);
    }

    /**
     * Adds parameters in the envelope.
     *
     * @param array $parameters
     * @param SimpleXMLElement $parametersParentNode
     */
    private function addParametersInEnvelope(array $parameters, SimpleXMLElement $parametersParentNode)
    {
        foreach($parameters as $key => $value) {
            if(\is_array($value)) {
                if(\is_numeric($key)){
                    $node = $parametersParentNode->addChild(\sprintf('item%s', $key));
                } else{
                    $node = $parametersParentNode->addChild($key);
                }

                $this->addParametersInEnvelope($value, $node);
            } else {
                $parametersParentNode->addChild($key, \htmlspecialchars($value));
            }
        }
    }
}

