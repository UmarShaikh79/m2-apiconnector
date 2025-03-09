# M2_APIConnector

## Overview
M2_APIConnector is a Magento 2 module designed to send REST API requests to external systems. Initially built for CommerceLink integration, this module is now designed to work with **any** API by extending its abstract class. It leverages **GuzzleHttp** for request handling and includes built-in logging for request/response tracking.

## Features
- **Abstract API Integration** – Extendable for any API without modifying the core logic.
- **Uses GuzzleHttp** – A modern PHP HTTP client.
- **Uses Symfony Serializer** – Handles request/response serialization.
- **Request & Response Logging** – Tracks all API interactions.
- **Magento 2 Compatible** – Supports PHP 7.4 to 8.2.
- **Configurable API Settings** – Allows setting content type, headers, service URLs, and authentication details dynamically.

## Installation
Ensure you have **Magento 2** and **Composer** installed.

1. Navigate to Magento root directory:
   ```sh
   cd /var/www/html/magento
   ```
2. Install via Composer:
   ```sh
   composer require m2/apiconnector symfony/serializer:^5.2
   ```
3. Enable the module:
   ```sh
   php bin/magento module:enable M2_APIConnector
   php bin/magento setup:upgrade
   php bin/magento cache:flush
   ```

## Usage
### Sending a REST API Request
The abstract class `\M2\APIConnector\Api\AbstractV1Api` provides a base structure for sending `POST` requests and logging responses. To implement a new API integration, extend this class and define your API-specific logic.

### Example:
```php
declare(strict_types=1);

namespace M2\APIConnector\Service;

use M2\APIConnector\Api\AbstractV1Api;
use M2\APIConnector\Api\ServiceApiInterface;
use M2\APIConnector\Data\Request\ServiceRequest;
use M2\APIConnector\Data\Response\ServiceResponse;

class ServiceApi extends AbstractV1Api implements ServiceApiInterface
{
    public function post(ServiceRequest $request): ServiceResponse
    {
        $body = $this->serializer->serialize($request, 'json', $this->getSerializerContext());

        /** @var \M2\APIConnector\Data\Response\ServiceResponse $response */
        $response = $this->sendRequestAndHandleResponse(
            $this->createHttpPostRequest($request->getHeaders(), $body),
            $this->createHttpClientOptions(),
            ServiceResponse::class
        );

        return $response;
    }
}
```

### Example REST API Call
Once you have implemented the required request and response objects, you can call the API service as follows:

```php
use M2\APIConnector\Service\ServiceApi;
use M2\APIConnector\Data\Request\ServiceRequest;

$apiService = new ServiceApi();
$request = new ServiceRequest();
$request->setSomeField('value');

$response = $apiService->post($request);
if ($response->isSuccess()) {
    echo "API call successful";
} else {
    echo "API call failed";
}
```

## Request and Response Objects
This module provides **Request and Response objects** to structure API data efficiently. Developers should implement these objects to handle API payloads.

### Example Request Object
```php
declare(strict_types=1);

namespace M2\APIConnector\Data\Request;

use M2\APIConnector\Api\Data\RequestInterface;

class ServiceRequest implements RequestInterface
{
    private ?string $orderNo;
    private string $startDate;
    private string $endDate;
    
    public function getOrderNo(): ?string
    {
        return $this->orderNo;
    }
    
    public function setOrderNo(?string $orderNo = null): void
    {
        $this->orderNo = $orderNo;
    }
    
    public function getStartDate(): string
    {
        return $this->startDate;
    }
    
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }
}
```

### Example Response Object
```php
declare(strict_types=1);

namespace M2\APIConnector\Data\Response;

class ServiceResponse
{
    private bool $success;
    private string $message;
    
    public function isSuccess(): bool
    {
        return $this->success;
    }
    
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
    
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
```

### Implementing a Request Object
To define a request structure, implement the `M2\APIConnector\Api\Data\RequestInterface`. Example:

- **Request Object Location**: `M2\APIConnector\Data\Request\`
- **Response Object Location**: `M2\APIConnector\Data\Response\`

Modify these files to create custom API request/response handling without affecting core logic.

## Configuration
The module provides a **flexible configuration interface** to set API request parameters dynamically. The `ConfigurationInterface` allows you to manage:
- **Service URL**
- **API Key**
- **Debug Mode & Logs**
- **Authentication Token URL**
- **Grant Type**
- **Client Credentials**
- **Content Type**

### Where to Update Configuration
To modify these settings, update the `M2\APIConnector\Api\Data\ConfigurationInterface` implementation. This ensures flexibility in handling different API integrations without modifying the core logic.

### Authorization (Bearer Token)
If your API requires authentication via a **Bearer Token**, the function `getAuthToken()` in `AbstractV1Api.php` is **commented by default**. To enable it, uncomment the function and ensure the necessary authentication parameters are configured in `ConfigurationInterface`.

## Release Notes
- **1.0.0**: Initial release with abstract API integration and logging.

## Author
- [Muhammad Umar](mailto:umarshiekh619@gmail.com)

## License
This module is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

