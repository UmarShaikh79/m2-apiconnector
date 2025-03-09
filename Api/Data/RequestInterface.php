<?php

/**
 * Copyright © 2024, All rights reserved.
 *
 * This file is part of the M2_IntegrationBridge module.
 *
 * This module provides a flexible integration layer using GuzzleHttp.
 * Licensed under the MIT License. See LICENSE file for details.
 */


declare(strict_types=1);

namespace M2\APIConnector\Api\Data;

interface RequestInterface
{
    /**
     * @return array[]
     */
    public function getHeaders(): array;
}
