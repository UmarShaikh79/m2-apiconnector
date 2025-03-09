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
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    /** @var \M2\APIConnector\Http\RequestFactory */
    private $factory;

    protected function setUp()
    {
        $this->factory = new RequestFactory();
    }

    public function testCreatePostRequestReturnsRequest(): void
    {
        $this->assertInstanceOf(Request::class, $this->factory->createPostRequest('http://example.com'));
    }

    public function testCreatePostRequestUseMethodPost(): void
    {
        $this->assertEquals(
            RequestFactory::METHOD_POST,
            $this->factory->createPostRequest('http://example.com')->getMethod()
        );
    }
}
