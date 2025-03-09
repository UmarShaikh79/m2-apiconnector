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

namespace M2\APIConnector\Data\Response;

class TokenizationResponse
{
    /** @var string|null */
    private $token_type;

    /** @var string|null */
    private $expires_in;

    /** @var string|null */
    private $ext_expire_in;

    /** @var string|null */
    private $expire_on;

    /** @var string|null */
    private $not_before;

    /** @var string|null */
    private $resource;

    /** @var string|null */
    private $access_token;

    public function getTokenType(): ?string
    {
        return $this->token_type;
    }

    public function setTokenType(?string $token_type = null): void
    {
        $this->token_type = $token_type;
    }

    public function getExpiresIn(): ?string
    {
        return $this->expires_in;
    }

    public function setExpiresIn(?string $expires_in = null): void
    {
        $this->expires_in = $expires_in;
    }

    public function getExtExpireIn(): ?string
    {
        return $this->ext_expire_in;
    }

    public function setExtExpireIn(?string $ext_expire_in = null): void
    {
        $this->ext_expire_in = $ext_expire_in;
    }

    public function getExpireOn(): ?string
    {
        return $this->expire_on;
    }

    public function setExpireOn(?string $expire_on = null): void
    {
        $this->expire_on = $expire_on;
    }

    public function getNotBefore(): ?string
    {
        return $this->not_before;
    }

    public function setNotBefore(?string $not_before = null): void
    {
        $this->not_before = $not_before;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(?string $resource = null): void
    {
        $this->resource = $resource;
    }

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function setAccessToken(?string $access_token = null): void
    {
        $this->access_token = $access_token;
    }
}
