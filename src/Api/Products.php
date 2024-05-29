<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

class Products extends AbstractPagedResource
{
    public function get(string $sku): array
    {
        return $this->resourceClient->getResource(sprintf('/rest/V1/products/%s', $sku));
    }

    protected function getPageUri(): string
    {
        return '/rest/V1/products';
    }
}
