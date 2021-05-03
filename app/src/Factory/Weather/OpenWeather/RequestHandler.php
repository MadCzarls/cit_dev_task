<?php

declare(strict_types=1);

namespace App\Factory\Weather\OpenWeather;

use App\DTO\Weather\CountryCity;
use App\Factory\Weather\AbstractRequestHandler;

use function http_build_query;

class RequestHandler extends AbstractRequestHandler
{
    private string $apiKey;
    private ?string $measurementUnit;

    public function __construct(
        string $host,
        string $apiKey,
        string $httpMethod,
        ?string $measurementUnit = null,
    ) {
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->httpMethod = $httpMethod;
        $this->measurementUnit = $measurementUnit;
    }

    protected function buildQueryParameters(CountryCity $countryCity): string
    {
        $params = [
            'q' => $countryCity->getCity() . ',' . $countryCity->getCountry(),
            'appid' => $this->apiKey,
        ];

        if ($this->measurementUnit) {
            $params['units'] = $this->measurementUnit;
        }

        return '?' . http_build_query($params);
    }
}
