<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

class StoreViews extends AbstractPagedResource
{
    protected function getPageUri(): string
    {
        return '/rest/V1/store/storeViews';
    }
}
