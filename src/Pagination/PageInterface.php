<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

interface PageInterface
{
    public function getFirstPage(): PageInterface;

    public function getPreviousPage(): ?PageInterface;

    public function getNextPage(): ?PageInterface;

    public function getCount(): int;

    public function getItems(): array;

    public function hasNextPage(): bool;

    public function hasPreviousPage(): bool;
}
