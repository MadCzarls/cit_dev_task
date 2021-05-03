<?php

declare(strict_types=1);

namespace App\Factory\Weather\OpenWeather;

use App\Factory\Weather\FactoryInterface;
use App\Factory\Weather\RequestHandlerInterface;
use App\Factory\Weather\ResponseHandlerInterface;
use JetBrains\PhpStorm\Pure;

class Factory implements FactoryInterface
{
    private string $name;
    private string $host;
    private string $apiKey;
    private string $httpMethod;
    private ?string $measurementUnit;

    public function __construct(
        string $name,
        string $host,
        string $apiKey,
        string $httpMethod,
        ?string $measurementUnit = null,
    ) {
        $this->name = $name;
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->httpMethod = $httpMethod;
        $this->measurementUnit = $measurementUnit;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function createRequestHandler(): RequestHandlerInterface
    {
        return new RequestHandler(
            $this->host,
            $this->apiKey,
            $this->httpMethod,
            $this->measurementUnit
        );
    }

    #[Pure] public function createResponseHandler(): ResponseHandlerInterface
    {
        return new ResponseHandler($this->getName());
    }
}
