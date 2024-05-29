<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient;

use ClickAndMortar\MagentoApiClient\Api\Customers;
use ClickAndMortar\MagentoApiClient\Api\Orders;
use ClickAndMortar\MagentoApiClient\Api\Products;
use ClickAndMortar\MagentoApiClient\Api\StoreViews;

class Client
{
    public function __construct(
        public Orders $orders,
        public Customers $customers,
        public StoreViews $storeViews,
        public Products $products
    )
    {
    }
}
