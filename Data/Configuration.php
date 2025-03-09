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

namespace M2\APIConnector\Data;

use M2\APIConnector\Api\Data\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /** @var string */
    private $apiKey = '';

    /** @var string */
    private $serviceUrl = 'https://sandbox.api.com';

    /** @var bool */
    private $debug = false;

    /** @var string */
    private $debugFile = 'php://output';

    /** @var string */
    private $authTokenUrl;

    /** @var string */
    private $grantType;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var string */
    private $contentType;

    /** @var string */
    private $userAgent;

    public function getServiceUrl(): string
    {
        return $this->serviceUrl;
    }

    public function setServiceUrl(string $serviceUrl): void
    {
        $this->serviceUrl = $serviceUrl;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    public function getDebug(): bool
    {
        return $this->debug;
    }

    public function setDebugFile(string $debugFile): void
    {
        $this->debugFile = $debugFile;
    }

    public function getDebugFile(): string
    {
        return $this->debugFile;
    }

    public function setAuthTokenUrl(string $authTokenUrl): void
    {
        $this->authTokenUrl = $authTokenUrl;
    }

    public function getAuthTokenUrl(): string
    {
        return $this->authTokenUrl;
    }

    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }
}
