<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\SearchCriteria;

class SearchCriteriaBuilder
{
    protected array $filterGroups = [];
    protected array $sortOrders = [];
    protected ?int $pageSize = null;
    protected ?int $currentPage = null;

    public function addFilter(string $field, mixed $value, string $conditionType = 'eq', int $filterGroup = 0): self
    {
        if (!isset($this->filterGroups[$filterGroup])) {
            $this->filterGroups[$filterGroup] = ['filters' => []];
        }

        $this->filterGroups[$filterGroup]['filters'][] = [
            'field'         => $field,
            'value'         => $value,
            'conditionType' => $conditionType
        ];

        return $this;
    }

    public function addSortOrder(string $field, string $direction = 'asc'): self
    {
        $this->sortOrders[] = [
            'field'     => $field,
            'direction' => $direction
        ];

        return $this;
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function create(): SearchCriteria
    {
        return new SearchCriteria(
            $this->filterGroups,
            $this->sortOrders,
            $this->currentPage ?? 1,
            $this->pageSize ?? SearchCriteria::DEFAULT_PAGE_SIZE
        );
    }
}
