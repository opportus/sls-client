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

/**
 * The checkGenerateLabel response interface.
 *
 * @package Opportus\SlsClient\Response
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
interface CheckGenerateLabelResponseInterface extends ResponseInterface
{
    /**
     * Gets the message ID.
     *
     * @return string
     */
    public function getMessageId();

    /**
     * Gets the label.
     *
     * @return null|string
     */
    public function getLabel();

    /**
     * Gets the parcel number.
     *
     * @return null|string
     */
    public function getParcelNumber();
}

