<?php

declare(strict_types=1);

namespace ClickAndMortar\MagentoApiClient\Client;

use ClickAndMortar\MagentoApiClient\Exception\{RedirectionHttpException,
    UnauthorizedHttpException,
    BadRequestHttpException,
    ClientErrorHttpException,
    ForbiddenHttpException,
    MethodNotAllowedHttpException,
    NotAcceptableHttpException,
    NotFoundHttpException,
    ServerErrorHttpException};
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpExceptionHandler
{
    /**
     * @throws UnauthorizedHttpException
     * @throws NotFoundHttpException
     * @throws ClientErrorHttpException
     * @throws ServerErrorHttpException
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotAcceptableHttpException
     * @throws MethodNotAllowedHttpException
     * @throws RedirectionHttpException
     */
    public function transformResponseToException(
        RequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($this->isSuccessStatusCode($response->getStatusCode())) {
            return $response;
        }

        if ($this->isRedirectionStatusCode($response->getStatusCode())) {
            throw new RedirectionHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_BAD_REQUEST === $response->getStatusCode()) {
            throw new BadRequestHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_UNAUTHORIZED === $response->getStatusCode()) {
            throw new UnauthorizedHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_FORBIDDEN === $response->getStatusCode()) {
            throw new ForbiddenHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_NOT_FOUND === $response->getStatusCode()) {
            throw new NotFoundHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_METHOD_NOT_ALLOWED === $response->getStatusCode()) {
            throw new MethodNotAllowedHttpException($this->getResponseMessage($response), $request, $response);
        }

        if (HttpClient::HTTP_NOT_ACCEPTABLE === $response->getStatusCode()) {
            throw new NotAcceptableHttpException($this->getResponseMessage($response), $request, $response);
        }

        if ($this->isApiClientErrorStatusCode($response->getStatusCode())) {
            throw new ClientErrorHttpException($this->getResponseMessage($response), $request, $response);
        }

        throw new ServerErrorHttpException($this->getResponseMessage($response), $request, $response);
    }

    /**
     * Returns the response message, or the reason phrase if there is none.
     */
    protected function getResponseMessage(ResponseInterface $response): string
    {
        $responseBody = $response->getBody();

        $responseBody->rewind();
        $decodedBody = json_decode($responseBody->getContents(), true);
        $responseBody->rewind();

        return $decodedBody['message'] ?? $response->getReasonPhrase();
    }

    private function isSuccessStatusCode(int $statusCode): bool
    {
        return in_array($statusCode, [
            HttpClient::HTTP_OK,
            HttpClient::HTTP_CREATED,
            HttpClient::HTTP_NO_CONTENT,
        ]);
    }

    private function isApiClientErrorStatusCode(int $statusCode): bool
    {
        return $statusCode >= 400 && $statusCode < 500;
    }

    private function isRedirectionStatusCode(int $statusCode): bool
    {
        return $statusCode >= 300 && $statusCode < 400;
    }
}
