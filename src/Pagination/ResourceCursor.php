<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

use ReturnTypeWillChange;

class ResourceCursor implements ResourceCursorInterface
{
    protected PageInterface $currentPage;
    protected int $currentIndex = 0;
    protected int $totalIndex = 0;

    public function __construct(
        protected int $pageSize,
        protected PageInterface $firstPage
    ) {
        $this->currentPage = $firstPage;
    }

    #[ReturnTypeWillChange]
    public function current(): mixed
    {
        return $this->currentPage->getItems()[$this->currentIndex];
    }

    public function next(): void
    {
        $this->currentIndex++;
        $this->totalIndex++;

        $items = $this->currentPage->getItems();

        if (!isset($items[$this->currentIndex]) && $this->currentPage->hasNextPage()) {
            $this->currentIndex = 0;
            $this->currentPage = $this->currentPage->getNextPage();
        }
    }

    #[ReturnTypeWillChange]
    public function key(): int
    {
        return $this->totalIndex;
    }

    public function valid(): bool
    {
        return isset($this->currentPage->getItems()[$this->currentIndex]);
    }

    public function rewind(): void
    {
        $this->totalIndex = 0;
        $this->currentIndex = 0;
        $this->currentPage = $this->firstPage;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }
}
