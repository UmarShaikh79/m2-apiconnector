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

namespace M2\APIConnector\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Magento\Framework\Filesystem\Io\File;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use M2\APIConnector\Api\Data\ConfigurationInterface;
use M2\APIConnector\Api\Data\ResponseInterface;
use M2\APIConnector\Data\Configuration;
use M2\APIConnector\Data\Response\TokenizationResponse;
use M2\APIConnector\Exception\ApiException;
use M2\APIConnector\Http\RequestFactory;

use function array_merge;
use function date;
use function floor;
use function strtotime;

abstract class AbstractV1Api
{
    private const IGNORED_ATTRIBUTES = 'ignored_attributes';

    /** @var array */
    protected $ignoredAttributes = ['headers'];

    /** @var \GuzzleHttp\ClientInterface */
    protected $client;

    /** @var \M2\APIConnector\Api\Data\ConfigurationInterface */
    protected $config;

    /** @var \M2\APIConnector\Http\RequestFactory */
    protected $requestFactory;

    /** @var \Psr\Log\LoggerInterface|null */
    private $logger;

    /** @var \Magento\Framework\Filesystem\Io\File */
    protected $file;

    /** @var \Symfony\Component\Serializer\SerializerInterface */
    protected $serializer;

    public function __construct(
        File $file,
        ?ConfigurationInterface $config = null,
        ?ClientInterface $client = null,
        ?RequestFactory $requestFactory = null,
        SerializerInterface $serializer = null,
        ?LoggerInterface $logger = null
    ) {
        $this->file = $file;
        $this->config = $config ?: new Configuration();
        $this->client = $client ?: new Client();
        $this->requestFactory = $requestFactory ?: new RequestFactory();
        $this->serializer = $serializer ?: $this->constructSerializer();
        $this->logger = $logger;
    }

    /**
     * @throws \M2\APIConnector\Exception\ApiException
     */
    protected function sendRequestAndHandleResponse(
        RequestInterface $httpRequest,
        array $httpOptions,
        string $responseClass,
        array $context = []
    ): ResponseInterface {

        $response = null;
        $startTimeStr = date('Y-m-d H:i:s');

        if ($this->logger !== null) {
            $this->logger->info('Request: ' . $httpRequest->getBody());
            $this->logger->info('Start Time: ' . $startTimeStr);
        }

        //Send Request
        try {
            $httpResponse = $this->client->send($httpRequest, $httpOptions);
        } catch (GuzzleException $exception) {
            throw new ApiException(
                $httpRequest,
                (string) $exception->getMessage(),
                (int) $exception->getCode(),
                $exception
            );
        }

        $stream = $httpResponse->getBody();
        $responseContent = $stream->getContents();
        $stream->rewind();

        $endTimeStr = date('Y-m-d H:i:s');
        $logTime = strtotime($endTimeStr) - strtotime($startTimeStr);
        $differenceInSeconds = floor($logTime);

        if ($this->logger !== null) {
            $this->logger->info('End Time: ' . $endTimeStr);
            $this->logger->info('Time Difference: ' . $differenceInSeconds);
            $this->logger->info('Response: ' . $responseContent);
        }

        if ($responseContent !== null && $responseContent !== '') {
            /** @var \M2\APIConnector\Api\Data\ResponseInterface $response */
            $response = $this->serializer->deserialize($responseContent, $responseClass, 'json', $context);
        }

        if (!$response instanceof $responseClass) {
            $response = new $responseClass();
        }

        $response->setHttpRequest($httpRequest);
        $response->setHttpResponse($httpResponse);

        $message = "Request Complete successfully";
        $response->setStatus(true);
        $response->setMessage($message);

        return $response;
    }

    protected function createHttpPostRequest(array $headers = [], ?string $httpBody = null): Request
    {
        $uri = $this->config->getServiceUrl();

        if ($this->logger !== null) {
            $this->logger->info('Path: ' . $uri);
        }

        /**
         * Enable it if you have authorization or barear token requirement
         */

        /**
        $authToken = $this->getAuthToken();

        $httpHeaders = [
            'Content-Type' => $this->config->getContentType(),
            'Authorization' => 'Bearer ' . $authToken->getAccessToken()
        ];
        */

        $httpHeaders = [
            'Content-Type' => $this->config->getContentType()
        ];

        if ($headers !== null) {
            $httpHeaders = array_merge($httpHeaders, $headers);
        }

        return $this->requestFactory->createPostRequest($uri, $httpHeaders, $httpBody);
    }

    protected function createHttpGetRequest(string $path, array $headers = [], string $httpBody = null): Request
    {
        $uri = $this->config->getServiceUrl();

        $uri = rtrim($uri, '/') . $path;

        if ($this->logger !== null) {
            $this->logger->info('Path: ' . $uri);
        }

        $httpHeaders = [
            'Content-Type' => 'application/json',
            'User-Agent' => $this->config->getUserAgent(),
            'x-api-key' => $this->config->getApiKey(),
        ];

        if (!empty($headers)) {
            $httpHeaders = array_merge($httpHeaders, $headers);
        }

        return $this->requestFactory->createGetRequest($uri, $httpHeaders, $httpBody);
    }

    protected function createHttpPatchRequest(string $path, array $headers = [], string $httpBody = null): Request
    {
        $uri = $this->config->getServiceUrl();

        $uri = rtrim($uri, '/') . $path;

        if ($this->logger !== null) {
            $this->logger->info('Path: ' . $uri);
        }
        $httpHeaders = [
            'Content-Type' => 'application/json',
            'User-Agent' => $this->config->getUserAgent(),
            'x-api-key' => $this->config->getApiKey(),
        ];

        if (!empty($headers)) {
            $httpHeaders = array_merge($httpHeaders, $headers);
        }

        return $this->requestFactory->createPatchRequest($uri, $httpHeaders, $httpBody);
    }

    /**
     * @throws \RuntimeException on file opening failure
     */
    protected function createHttpClientOptions(): array
    {
        $options = [];
        if ($this->config->getDebug()) {
            $debugFile = $this->config->getDebugFile();

            if ($debugFile === null || $debugFile === '') {
                throw new RuntimeException("Failed to open the debug file, filename is blank: $debugFile");
            }

            $options[RequestOptions::DEBUG] = $this->file->open(['path' => $debugFile]);

            if (!$options[RequestOptions::DEBUG]) {
                throw new RuntimeException("Failed to open the debug file: $debugFile");
            }
        }

        return $options;
    }

    protected function constructSerializer(): SerializerInterface
    {
        $encoders = [
            new JsonEncoder(),
        ];
        $normalizers = [
            new JsonSerializableNormalizer(),
            new ObjectNormalizer(null, null, null, new ReflectionExtractor()),
            new ArrayDenormalizer(),
        ];

        return new Serializer($normalizers, $encoders);
    }

    protected function getSerializerContext(): array
    {
        return [self::IGNORED_ATTRIBUTES => $this->ignoredAttributes];
    }

    /**
     * @throws \M2\APIConnector\Exception\ApiException
     */
    private function getAuthToken(): TokenizationResponse
    {
        $response = null;
        $responseClass = TokenizationResponse::class;

        $authUrl = $this->config->getAuthTokenUrl();
        $httpBody = [
            'form_params' => [
                'grant_type' => $this->config->getGrantType(),
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
                'resource' => $this->config->getServiceUrl()
            ]
        ];

        $httpResponse = $this->client->request(RequestFactory::METHOD_POST, $authUrl, $httpBody);
        $stream = $httpResponse->getBody();
        $responseContent = $stream->getContents();
        $stream->rewind();

        if ($responseContent !== null && $responseContent !== '') {
            /** @var \M2\APIConnector\Data\Response\TokenizationResponse $response */
            $response = $this->serializer->deserialize(
                $responseContent,
                TokenizationResponse::class,
                'json'
            );
        }

        if (!$response instanceof $responseClass) {
            $response = new $responseClass();
        }

        return $response;
    }
}
