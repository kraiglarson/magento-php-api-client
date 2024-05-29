<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Security;

use Psr\Http\Message\RequestInterface;

class Authentication
{
    protected ?string $consumerKey = null;
    protected ?string $consumerSecret = null;
    protected ?string $accessToken = null;
    protected ?string $accessTokenSecret = null;

    public static function fromOauthCredentials(
        string $consumerKey,
        string $consumerSecret,
        string $accessToken,
        string $accessTokenSecret
    ): static
    {
        $authentication = new static();
        $authentication->consumerKey = $consumerKey;
        $authentication->consumerSecret = $consumerSecret;
        $authentication->accessToken = $accessToken;
        $authentication->accessTokenSecret = $accessTokenSecret;

        return $authentication;
    }

    public function generateOauthSignature(
        RequestInterface $request,
        int $timestamp,
        string $nonce,
        string $signatureMethod = 'HMAC-SHA256',
        string $version = '1.0'
    ): string
    {
        $parameters = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => $nonce,
            'oauth_signature_method' => $signatureMethod,
            'oauth_timestamp' => $timestamp,
            'oauth_token' => $this->accessToken,
            'oauth_version' => $version,
        ];

        $queryString = $request->getUri()->getQuery();
        $queryParams = array_map('urldecode', explode('&', $queryString));
        $queryParams = array_map(fn($param) => explode('=', $param), $queryParams);
        $queryParams = array_column($queryParams, 1, 0);

        $parameters = array_merge($parameters, $queryParams);

        uksort($parameters, function ($a, $b) use ($parameters) {
            $a = $a . $parameters[$a];
            $b = $b . $parameters[$b];
            return strcmp($a, $b);
        });

        $baseStrings = [
            $request->getMethod(),
            rawurlencode(explode('?', $request->getUri()->__toString())[0]),
            rawurlencode(http_build_query($parameters, '', '&', PHP_QUERY_RFC3986))
        ];

        $key = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->accessTokenSecret);

        return base64_encode(hash_hmac('sha256', implode('&', $baseStrings), $key, true));
    }

    public function getConsumerKey(): ?string
    {
        return $this->consumerKey;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
