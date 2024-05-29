<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Exception;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BaseException extends Exception
{
    public function __construct(
        string $message,
        protected RequestInterface $request,
        protected ResponseInterface $response
    ) {
        parent::__construct($message);
    }

    // TODO: __toString
}
