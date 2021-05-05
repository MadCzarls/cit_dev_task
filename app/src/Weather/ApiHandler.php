<?php

declare(strict_types=1);

namespace App\Weather;

use App\DTO\Weather\CountryCity;
use App\DTO\Weather\TemperatureResult;
use App\Exception\ApiIncorrectStatusException;
use App\Factory\Weather\FactoryInterface;
use App\Factory\Weather\RequestHandlerInterface;
use App\Factory\Weather\ResponseHandlerInterface;
use App\RequestApi\Builder\RequestBuilder;
use App\RequestApi\Client\ClientInterface;
use App\RequestApi\Http\RequestInterface;
use App\RequestApi\Http\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

use function iterator_to_array;
use function sprintf;

class ApiHandler
{
    /** @var FactoryInterface[] */
    private array $apiFactories;
    private LoggerInterface $logger;
    private ClientInterface $client;
    private ?FactoryInterface $factory = null;
    private ?RequestHandlerInterface $requestHandler = null;
    private ?ResponseHandlerInterface $responseHandler = null;
    private ?RequestBuilder $apiRequestBuilder = null;

    /**
     * @param FactoryInterface[] $apiFactories
     */
    public function __construct(
        iterable $apiFactories,
        LoggerInterface $logger,
        ClientInterface $client
    ) {
        $this->apiFactories = iterator_to_array($apiFactories);
        $this->logger = $logger;
        $this->client = $client;
    }

    /**
     * @return TemperatureResult[]
     */
    public function getResults(CountryCity $countryCity): array
    {
        $apiResults = [];

        foreach ($this->apiFactories as $factory) {
            try {
                $this->setAttributes($factory);
                $apiRequest = $this->prepareRequestForCountryAndCity($countryCity);
                $apiResponse = $this->executeRequest($apiRequest);
                $temperatureResult = $this->handleResponse($apiResponse);
            } catch (Throwable $exception) {
                $this->logger->error(
                    sprintf("An error occurred during handling API '%s'", $this->factory->getName()),
                    [
                        'exception' => $exception->getMessage(),
                        'trace' => $exception->getTraceAsString(),
                    ]
                );
                continue;
            }

            $apiResults[] = $temperatureResult;
        }

        return $apiResults;
    }

    private function setAttributes(FactoryInterface $factory): void
    {
        $this->factory = $factory;
        $this->apiRequestBuilder = new RequestBuilder();
        $this->requestHandler = $factory->createRequestHandler();
        $this->responseHandler = $factory->createResponseHandler();
        $this->requestHandler->setBuilder($this->apiRequestBuilder);
    }

    private function prepareRequestForCountryAndCity(CountryCity $countryCity): ?RequestInterface
    {
        $this->requestHandler->prepareRequestForCountryAndCity($countryCity);

        return $this->apiRequestBuilder->build();
    }

    private function executeRequest(RequestInterface $request): ?ResponseInterface
    {
        $result = $this->client->execute($request);
        if ($result->getStatusCode() !== Response::HTTP_OK) {
            throw new ApiIncorrectStatusException(
                sprintf("Response code from API '%s' is not 200'", $this->factory->getName())
            );
        }

        return $result;
    }

    private function handleResponse(ResponseInterface $response): ?TemperatureResult
    {
        return $this->responseHandler->handle($response);
    }
}
