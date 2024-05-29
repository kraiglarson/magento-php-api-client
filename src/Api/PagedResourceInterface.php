<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

use ClickAndMortar\MagentoApiClient\Pagination\PageInterface;
use ClickAndMortar\MagentoApiClient\Pagination\ResourceCursorInterface;
use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

interface PagedResourceInterface extends ResourceInterface
{
    public function listPerPage(?SearchCriteria $searchCriteria = null): PageInterface;

    public function all(?SearchCriteria $searchCriteria = null): ResourceCursorInterface;
}
