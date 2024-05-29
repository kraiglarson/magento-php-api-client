<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

class Customers extends AbstractPagedResource
{
    public function get(int $id): array
    {
        return $this->resourceClient->getResource(sprintf('/rest/V1/customers/%d', $id));
    }

    protected function getPageUri(): string
    {
        return '/rest/V1/customers';
    }
}
