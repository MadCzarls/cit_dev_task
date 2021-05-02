<?php

declare(strict_types=1);

namespace App\Factory\OpenWeatherMap;

use App\DTO\Weather\TemperatureResult;
use App\Factory\ResponseHandlerInterface;
use App\RequestApi\Http\ResponseInterface;

class ResponseHandler implements ResponseHandlerInterface
{
    public function handle(ResponseInterface $response): TemperatureResult
    {
        //@TODO substring on content, etc, check http status

        return new TemperatureResult('OpenWeatherMap', 21.3);
    }
}
