<?php

declare(strict_types=1);

namespace App\Factory\OpenWeatherMap;

use App\Factory\FactoryInterface;
use App\Factory\RequestHandlerInterface;
use App\Factory\ResponseHandlerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Factory implements FactoryInterface
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function createRequestHandler(): RequestHandlerInterface
    {
        return new RequestHandler($this->parameterBag);
    }

    public function createResponseHandler(): ResponseHandlerInterface
    {
        // TODO: Implement createResponseHandler() method.
    }
}
