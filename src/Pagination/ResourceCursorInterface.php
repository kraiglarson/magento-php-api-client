<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Pagination;

use Iterator;

interface ResourceCursorInterface extends Iterator
{
    public function getPageSize(): int;
}
