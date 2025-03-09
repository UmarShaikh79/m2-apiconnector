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

use Psr\Http\Message\RequestInterface as HttpRequestInterface;
use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface
{
    public function getHttpRequest(): HttpRequestInterface;

    public function setHttpRequest(HttpRequestInterface $httpRequest): void;

    public function getHttpResponse(): HttpResponseInterface;

    public function setHttpResponse(HttpResponseInterface $httpResponse): void;

    public function setHasErrors(bool $hasError = false): void;

    public function getHasErrors(): bool;

    public function setErrors(string $offset, string $label): void;

    public function getErrors(): array;

    public function getStatus(): ?bool;

    public function setStatus(bool $status): void;

    public function getMessage(): ?string;

    public function setMessage(string $message): void;
}
