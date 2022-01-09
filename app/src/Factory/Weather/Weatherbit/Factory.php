<?php

declare(strict_types=1);

namespace App\Factory\Weather\Weatherbit;

use App\Factory\Weather\FactoryInterface;
use App\Factory\Weather\RequestHandlerInterface;
use App\Factory\Weather\ResponseHandlerInterface;

class Factory implements FactoryInterface
{
    private string $name;
    private string $host;
    private string $apiKey;
    private string $httpMethod;

    public function __construct(
        string $name,
        string $host,
        string $apiKey,
        string $httpMethod,
    ) {
        $this->name = $name;
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->httpMethod = $httpMethod;
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
            $this->httpMethod
        );
    }

    public function createResponseHandler(): ResponseHandlerInterface
    {
        return new ResponseHandler($this->getName());
    }
}
