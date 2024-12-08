<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient;

use ClickAndMortar\MagentoApiClient\Api\Customers;
use ClickAndMortar\MagentoApiClient\Api\Orders;
use ClickAndMortar\MagentoApiClient\Api\Products;
use ClickAndMortar\MagentoApiClient\Api\ProductAttributes;
use ClickAndMortar\MagentoApiClient\Api\StoreViews;
use ClickAndMortar\MagentoApiClient\Client\HttpClient;
use ClickAndMortar\MagentoApiClient\Client\ResourceClient;
use ClickAndMortar\MagentoApiClient\Pagination\PageFactory;
use ClickAndMortar\MagentoApiClient\Pagination\ResourceCursorFactory;
use ClickAndMortar\MagentoApiClient\Security\Authentication;
use ClickAndMortar\MagentoApiClient\Utils\UriGenerator;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ClientBuilder
{
    private ?\Psr\Http\Client\ClientInterface $httpClient = null;
    private ?RequestFactoryInterface $requestFactory = null;
    private ?StreamFactoryInterface $streamFactory = null;

    public function __construct(
        protected string $baseUrl,
        protected array $options = []
    )
    {
    }

    public function buildAuthenticatedByOauth(
        string $consumerKey,
        string $consumerSecret,
        string $accessToken,
        string $accessTokenSecret
    ): Client
    {
        $authentication = Authentication::fromOauthCredentials(
            $consumerKey,
            $consumerSecret,
            $accessToken,
            $accessTokenSecret
        );

        $uriGenerator = new UriGenerator($this->baseUrl);
        $httpClient = new HttpClient(
            $this->getHttpClient(),
            $this->getRequestFactory(),
            $this->getStreamFactory(),
            $authentication
        );

        $resourceClient = new ResourceClient(
            $httpClient,
            $uriGenerator
        );

        $resourceCursorFactory = new ResourceCursorFactory();

        return new Client(
            new Orders($resourceClient, $resourceCursorFactory),
            new Customers($resourceClient, $resourceCursorFactory),
            new StoreViews($resourceClient, $resourceCursorFactory),
            new Products($resourceClient, $resourceCursorFactory),
            new ProductAttributes($resourceClient, $resourceCursorFactory)
        );
    }

    private function getHttpClient(): \Psr\Http\Client\ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = Psr18ClientDiscovery::find();
        }

        return $this->httpClient;
    }

    private function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->requestFactory) {
            $this->requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        }

        return $this->requestFactory;
    }

    private function getStreamFactory(): StreamFactoryInterface
    {
        if (null === $this->streamFactory) {
            $this->streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        }

        return $this->streamFactory;
    }
}
