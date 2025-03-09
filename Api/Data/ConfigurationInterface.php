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

interface ConfigurationInterface
{
    public function getServiceUrl(): string;

    public function setServiceUrl(string $serviceUrl): void;

    public function getApiKey(): string;

    public function setApiKey(string $apiKey): void;

    public function getDebug(): bool;

    public function setDebug(bool $debug): void;

    public function getDebugFile(): string;

    public function setDebugFile(string $debugFile): void;

    public function setAuthTokenUrl(string $authTokenUrl): void;

    public function getAuthTokenUrl(): string;

    public function setGrantType(string $grantType): void;

    public function getGrantType(): string;

    public function setClientId(string $clientId): void;

    public function getClientId(): string;

    public function setClientSecret(string $clientSecret): void;

    public function getClientSecret(): string;

    public function setContentType(string $contentType): void;

    public function getContentType(): string;

    public function getUserAgent(): string;

    public function setUserAgent(string $userAgent): void;
}
