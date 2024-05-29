<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

use ClickAndMortar\MagentoApiClient\Api\PagedResourceInterface;
use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

class PageFactory
{
    public function __construct(
        protected PagedResourceInterface $pagedResource,
    ) {
    }

    public function createPage(array $data, SearchCriteria $searchCriteria): PageInterface
    {
        $totalCount = $data['total_count'] ?? count($data);
        $items = $data['items'] ?? $data;

        return new Page($this->pagedResource, $items, $totalCount, $searchCriteria);
    }
}
