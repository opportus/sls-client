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

use Opportus\SlsClient\Response\CheckGenerateLabelResponseInterface;
use Opportus\SlsClient\Response\GenerateLabelResponseInterface;

/**
 * The client interface.
 *
 * @package Opportus\SlsClient
 * @author  Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
interface ClientInterface
{
    /**
     * Creates the client.
     *
     * @return ClientInterface
     */
    public static function create(): ClientInterface;

    /**
     * Generates a label.
     *
     * @param array $parameters
     * @return GenerateLabelResponseInterface
     * @throws InvalidArgumentException
     */
    public function generateLabel(array $parameters): GenerateLabelResponseInterface;

    /**
     * Checks generateLabel.
     *
     * @param array $parameters
     * @return CheckGenerateLabelResponseInterface
     * @throws InvalidArgumentException
     */
    public function checkGenerateLabel(array $parameters): CheckGenerateLabelResponseInterface;
}

