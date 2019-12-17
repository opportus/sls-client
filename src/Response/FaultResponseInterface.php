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
 * The fault response interface.
 *
 * @package Opportus\SlsClient\Response
 * @author Clément Cazaud <clement.cazaud@gmail.com>
 * @license https://github.com/opportus/sls-client/blob/master/LICENSE MIT
 */
interface FaultResponseInterface extends ResponseInterface
{
    /**
     * Gets the code.
     *
     * @return string
     */
    public function getCode();

    /**
     * Gets the string.
     *
     * @return string
     */
    public function getString();
}

