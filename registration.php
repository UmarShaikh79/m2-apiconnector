<?php

/**
 * Copyright © 2024, All rights reserved.
 *
 * This file is part of the M2_IntegrationBridge module.
 *
 * This module provides a flexible integration layer using GuzzleHttp.
 * Licensed under the MIT License. See LICENSE file for details.
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'M2_APIConnector', __DIR__);
