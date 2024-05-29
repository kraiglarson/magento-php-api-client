<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Client;

use ClickAndMortar\MagentoApiClient\Security\Authentication;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

class HttpClient
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_TOO_MANY_REQUESTS = 429;

    protected HttpExceptionHandler $httpExceptionHandler;

    public function __construct(
        protected ClientInterface $httpClient,
        protected RequestFactoryInterface $requestFactory,
        protected StreamFactoryInterface $streamFactory,
        protected Authentication $authentication
    ) {
        $this->httpExceptionHandler = new HttpExceptionHandler();
    }

    public function sendRequest(string $httpMethod, $uri, array $headers = [], $body = null): ResponseInterface
    {
        $request = $this->prepareRequest($httpMethod, $uri, $headers, $body);

        $timestamp = time();
        $nonce = md5(uniqid());

        $signature = $this->authentication->generateOauthSignature(
            $request,
            $timestamp,
            $nonce
        );

        $authorization = sprintf(
            'OAuth oauth_consumer_key="%s", oauth_token="%s", oauth_signature_method="HMAC-SHA256", oauth_signature="%s", oauth_timestamp="%s", oauth_nonce="%s", oauth_version="1.0"',
            $this->authentication->getConsumerKey(),
            $this->authentication->getAccessToken(),
            $signature,
            $timestamp,
            $nonce
        );

        $request = $request->withHeader('Authorization', $authorization);

        $response = $this->httpClient->sendRequest($request);

        return $this->httpExceptionHandler->transformResponseToException($request, $response);
    }

    private function prepareRequest(string $httpMethod, $uri, array $headers = [], $body = null): RequestInterface
    {
        $request = $this->requestFactory->createRequest($httpMethod, $uri);

        foreach ($headers as $header => $content) {
            $request = $request->withHeader($header, $content);
        }

        return $request;
    }
}
