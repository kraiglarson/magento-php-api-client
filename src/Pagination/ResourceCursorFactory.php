<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

class ResourceCursorFactory implements ResourceCursorFactoryInterface
{
    public function createCursor(PageInterface $firstPage, SearchCriteria $searchCriteria): ResourceCursorInterface
    {
        return new ResourceCursor($searchCriteria->getPageSize(), $firstPage);
    }
}
