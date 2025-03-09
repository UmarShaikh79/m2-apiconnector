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

namespace M2\APIConnector\Exception;

use Exception;
use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Throwable;

class ApiException extends Exception
{
    /** @var \Psr\Http\Message\RequestInterface */
    private $httpRequest;

    public function __construct(
        HttpRequestInterface $httpRequest,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->httpRequest = $httpRequest;
    }

    public function getHttpRequest(): HttpRequestInterface
    {
        return $this->httpRequest;
    }
}
