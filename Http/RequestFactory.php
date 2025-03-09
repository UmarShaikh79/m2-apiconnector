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

namespace M2\APIConnector\Http;

use GuzzleHttp\Psr7\Request;

class RequestFactory
{
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';
    public const METHOD_PATCH = 'PATCH';

    public function createPostRequest(
        string $uri,
        array $headers = [],
        ?string $body = null,
        string $version = '1.1'
    ): Request {
        return new Request(self::METHOD_POST, $uri, $headers, $body, $version);
    }

    public function createGetRequest(
        string $uri,
        array $headers = [],
        string $body = null,
        string $version = '1.1'
    ): Request {
        return new Request(self::METHOD_GET, $uri, $headers, $body, $version);
    }

    public function createPatchRequest(
        string $uri,
        array $headers = [],
        string $body = null,
        string $version = '1.1'
    ): Request {
        return new Request(self::METHOD_PATCH, $uri, $headers, $body, $version);
    }
}
