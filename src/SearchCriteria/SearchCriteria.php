<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\SearchCriteria;

class SearchCriteria
{
    public const DEFAULT_PAGE_SIZE = 10;

    public function __construct(
        protected array $filterGroups = [],
        protected array $sortOrders = [],
        protected int $currentPage = 1,
        protected int $pageSize = self::DEFAULT_PAGE_SIZE
    )
    {
    }

    public function getFilterGroups(): array
    {
        return $this->filterGroups;
    }

    public function setFilterGroups(?array $filterGroups = null): self
    {
        $this->filterGroups = $filterGroups ?? [];

        return $this;
    }

    public function getSortOrders(): array
    {
        return $this->sortOrders;
    }

    public function setSortOrders(?array $sortOrders = null): self
    {
        $this->sortOrders = $sortOrders ?? [];

        return $this;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function toQueyParams(): array
    {
        $params = [
            'searchCriteria' => [],
        ];

        if (!empty($this->filterGroups)) {
            $params['searchCriteria']['filterGroups'] = $this->filterGroups;
        }

        if (!empty($this->sortOrders)) {
            $params['searchCriteria']['sortOrders'] = $this->sortOrders;
        }

        $params['searchCriteria']['pageSize'] = $this->pageSize;

        if ($this->currentPage !== 1) {
            $params['searchCriteria']['currentPage'] = $this->currentPage;
        }

        return $params;
    }
}
