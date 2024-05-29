<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

class Orders extends AbstractPagedResource
{
    public function get(string $incrementId): array
    {
        return $this->resourceClient->getResource(sprintf('/rest/V1/orders/%s', $incrementId));
    }

    protected function getPageUri(): string
    {
        return '/rest/V1/orders';
    }
}
