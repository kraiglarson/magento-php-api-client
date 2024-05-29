<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Client;

use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;
use ClickAndMortar\MagentoApiClient\Utils\UriGenerator;

class ResourceClient
{
    public function __construct(
        protected HttpClient $httpClient,
        protected UriGenerator $uriGenerator
    ) {
    }

    public function getResource(string $uri, ?SearchCriteria $searchCriteria = null): array
    {
        if ($searchCriteria === null) {
            $searchCriteria = new SearchCriteria();
        }

        $uri = $this->uriGenerator->generate($uri, $searchCriteria);

        $response = $this->httpClient->sendRequest('GET', $uri, ['Accept' => '*/*']);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getResources(
        string $uri,
        ?SearchCriteria $searchCriteria = null
    ): array {
        if ($searchCriteria === null) {
            $searchCriteria = new SearchCriteria();
        }

        return $this->getResource($uri, $searchCriteria);
    }
}
