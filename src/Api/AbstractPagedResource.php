<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Api;

use ClickAndMortar\MagentoApiClient\Client\ResourceClient;
use ClickAndMortar\MagentoApiClient\Pagination\PageFactory;
use ClickAndMortar\MagentoApiClient\Pagination\PageInterface;
use ClickAndMortar\MagentoApiClient\Pagination\ResourceCursorFactoryInterface;
use ClickAndMortar\MagentoApiClient\Pagination\ResourceCursorInterface;
use ClickAndMortar\MagentoApiClient\SearchCriteria\SearchCriteria;

abstract class AbstractPagedResource implements PagedResourceInterface
{
    protected PageFactory $pageFactory;

    public function __construct(
        protected ResourceClient $resourceClient,
        protected ResourceCursorFactoryInterface $cursorFactory
    ) {
        $this->pageFactory = new PageFactory($this);
    }

    public function listPerPage(?SearchCriteria $searchCriteria = null): PageInterface
    {
        if ($searchCriteria === null) {
            $searchCriteria = new SearchCriteria();
        }

        $data = $this->resourceClient->getResources($this->getPageUri(), $searchCriteria);

        return $this->pageFactory->createPage($data, $searchCriteria);
    }

    public function all(?SearchCriteria $searchCriteria = null): ResourceCursorInterface
    {
        if ($searchCriteria === null) {
            $searchCriteria = new SearchCriteria();
        }

        $firstPage = $this->listPerPage($searchCriteria);

        return $this->cursorFactory->createCursor($firstPage, $searchCriteria);
    }

    abstract protected function getPageUri(): string;
}
