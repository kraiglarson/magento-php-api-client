<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

class ProductAttributes extends AbstractPagedResource
{
    public function get(string $sku): array
    {
        /*
         * Note from Kraig Larson <kraig@kraiglarson.com> -- see https://magento.stackexchange.com/questions/303779/magento-2-get-value-of-custom-attribute-on-magento-2-rest-api-v1-orders-items/313072#313072
        */
        return $this->resourceClient->getResource(sprintf('/rest/V1/products/attributes/%s', $sku));
    }

    protected function getPageUri(): string
    {
        return '/rest/V1/products/attributes';
    }
}
