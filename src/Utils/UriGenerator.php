<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Utils;

use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

class UriGenerator
{
    protected string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function generate(string $path, SearchCriteria $searchCriteria): string
    {
        $url = sprintf('%s/%s', $this->baseUrl, ltrim($path, '/'));

        $queryParameters = $searchCriteria->toQueyParams();
        if (!empty($queryParameters)) {
            $url .= '?' . http_build_query($queryParameters, '', '&', PHP_QUERY_RFC3986);
        }

        return $url;
    }
}
