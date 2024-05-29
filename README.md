# Magento / Adobe Commerce PHP API client

This is a PHP client for the Magento / Adobe Commerce REST API.

## Installation

```bash
composer require clickandmortar/magento-api-client
```

## Usage

```php
<?php

require 'vendor/autoload.php';

use ClickAndMortar\MagentoApiClient\ClientBuilder;
use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteriaBuilder;

$clientBuilder = new ClientBuilder('https://magento.hostname.com/');
$client = $clientBuilder->buildAuthenticatedByOauth(
    '<consumer-key>>',
    '<consumer-secret>
    '<access-token>',
    '<access-token-secret>'
);

// Fetch all products
$searchCriteriaBuilder = new SearchCriteriaBuilder();
$searchCriteriaBuilder->addFilter('type_id', 'simple');
$searchCriteriaBuilder->setPageSize(10);

foreach ($client->products->all($searchCriteriaBuilder->create()) as $product) {
    echo $product->sku . ' - ' . $product->name . PHP_EOL;
}

// Fetch a single product
$product = $client->products->get('24-MB01');
```

## Available resources

- `products`
- `orders`
- `customers`

## Credits

This library is heavily inspired by the [Akeneo PHP Client](https://github.com/akeneo/api-php-client), thanks for their amazing work 🧡.
