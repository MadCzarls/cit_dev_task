<?php

declare(strict_types=1);

namespace App\Factory\Weather\Weatherbit;

use App\DTO\Weather\TemperatureResult;
use App\Factory\Weather\AbstractResponseHandler;
use App\RequestApi\Http\ResponseInterface;

use function json_decode;

class ResponseHandler extends AbstractResponseHandler
{
    public function handle(ResponseInterface $response): TemperatureResult
    {
        $content = json_decode($response->getBody(), true, flags: JSON_THROW_ON_ERROR);
        $temperature = (float) $content['data'][0]['temp'];

        return new TemperatureResult($this->name, $temperature);
    }
}
