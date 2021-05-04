<?php

declare(strict_types=1);

namespace App\RequestApi\Client;

use App\RequestApi\Http\RequestInterface;
use App\RequestApi\Http\Response;
use App\RequestApi\Http\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;

class SymfonyHttpClient implements ClientInterface
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function execute(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->request(
            $request->getHttpMethodEnum()->getValue(),
            $request->getUrl(),
        );

        try {
            $response->getStatusCode();
        } catch (ClientException $exception) {
            $this->logger->warning(
                sprintf("Symfony http/client threw exception: '%s'", $exception->getMessage())
            );
        } finally {
            $clientResponse = new Response($response->getStatusCode(), $response->getContent());
        }

        return $clientResponse;
    }
}
