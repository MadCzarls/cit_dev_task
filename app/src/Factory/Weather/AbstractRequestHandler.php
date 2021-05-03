<?php

declare(strict_types=1);

namespace App\Factory\Weather;

use App\DTO\Weather\CountryCity;
use App\Enum\RequestHttpMethodEnum;
use App\RequestApi\Builder\RequestBuilderInterface;
use InvalidArgumentException;

abstract class AbstractRequestHandler implements RequestHandlerInterface
{
    protected string $host;
    protected string $httpMethod;
    protected ?RequestBuilderInterface $builder = null;

    public function setBuilder(RequestBuilderInterface $builder): void
    {
        $this->builder = $builder;
    }

    public function prepareRequestForCountryAndCity(CountryCity $countryCity): void
    {
        if (!$this->builder) {
            throw new InvalidArgumentException('Builder must be set');
        }

        $this->builder->reset();

        $url = $this->host . $this->buildQueryParameters($countryCity);

        $this->builder->setUrl($url);
        $this->builder->setHttpMethod(RequestHttpMethodEnum::from($this->httpMethod));
    }

    abstract protected function buildQueryParameters(CountryCity $countryCity): string;
}