<?php

declare(strict_types=1);

namespace App\Factory\Weather\Weatherbit;

use App\DTO\Weather\CountryCity;
use App\Factory\Weather\AbstractRequestHandler;

use function http_build_query;

class RequestHandler extends AbstractRequestHandler
{
    private string $apiKey;

    public function __construct(
        string $host,
        string $apiKey,
        string $httpMethod,
    ) {
        $this->host = $host;
        $this->apiKey = $apiKey;
        $this->httpMethod = $httpMethod;
    }

    protected function buildQueryParameters(CountryCity $countryCity): string
    {
        $params = [
            'city' => $countryCity->getCity(),
            'country' => $countryCity->getCountry(),
            'key' => $this->apiKey,
        ];

        return '?' . http_build_query($params);
    }
}
