<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

use ClickAndMortar\MagentoApiClient\Api\PagedResourceInterface;
use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

class Page implements PageInterface
{
    public function __construct(
        protected PagedResourceInterface $pagedResource,
        protected array $items,
        protected int $totalCount,
        protected SearchCriteria $searchCriteria
    ) {
    }

    public function getFirstPage(): PageInterface
    {
        return $this->getPage(1);
    }

    public function getPreviousPage(): ?PageInterface
    {
        return $this->hasPreviousPage() ? $this->getPage($this->getCurrent() - 1) : null;
    }

    public function getNextPage(): ?PageInterface
    {
        return $this->hasNextPage() ? $this->getPage($this->getCurrent() + 1) : null;
    }

    public function getCount(): int
    {
        return count($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function hasNextPage(): bool
    {
        return count($this->items) && ($this->getCurrent() * count($this->items)) < $this->totalCount;
    }

    public function hasPreviousPage(): bool
    {
        return $this->getCurrent() > 1;
    }

    public function getCurrent(): int
    {
        return $this->searchCriteria->getCurrentPage();
    }

    protected function getPage(int $page): PageInterface
    {
        return $this->pagedResource->listPerPage($this->searchCriteria->setCurrentPage($page));
    }
}
