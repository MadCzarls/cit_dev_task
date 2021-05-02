<?php

declare(strict_types=1);

namespace App\Factory\OpenWeatherMap;

use App\DTO\Weather\CountryCity;
use App\Enum\RequestHttpMethodEnum;
use App\Factory\RequestHandlerInterface;
use App\RequestApi\Builder\RequestBuilderInterface;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use function http_build_query;

class RequestHandler implements RequestHandlerInterface
{
    private string $host;
    private string $apiKey;
    private string $httpMethod;
    private ?RequestBuilderInterface $builder = null;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->host = $parameterBag->get('openweathermap.host');
        $this->apiKey = $parameterBag->get('openweathermap.apiKey');
        $this->httpMethod = $parameterBag->get('openweathermap.http_method');
    }

    public function requestForCountryAndCity(CountryCity $countryCity): void
    {
        if (!$this->builder) {
            throw new InvalidArgumentException('Builder must be set');
        }

        $url = $this->host . $this->buildQueryParameters($countryCity);

        $this->builder->setUrl($url);
        $this->builder->setHttpMethod(RequestHttpMethodEnum::from($this->httpMethod));
    }

    private function buildQueryParameters(CountryCity $countryCity): string
    {
        //@TODO refactor into URL builder and sanitizer (remove polish characters dor example)

        return '?' . http_build_query(
            [
                'q' => $countryCity->getCity() . ',' . $countryCity->getCountry(),
                'appid' => $this->apiKey,
            ]
        );
    }

    public function setBuilder(RequestBuilderInterface $builder): void
    {
        $this->builder = $builder;
    }
}
