<?php

declare(strict_types=1);

namespace App\RequestApi\Client;

use App\RequestApi\Http\RequestInterface;
use App\RequestApi\Http\Response;
use App\RequestApi\Http\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyHttpClient implements ClientInterface
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function execute(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->request(
            $request->getHttpMethodEnum()->getValue(),
            $request->getUrl(),
        );

        return new Response($response->getStatusCode(), $response->getContent());
    }
}
