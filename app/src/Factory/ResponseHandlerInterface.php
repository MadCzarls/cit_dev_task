<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\Weather\TemperatureResult;
use App\RequestApi\Http\ResponseInterface;

interface ResponseHandlerInterface
{
    public function handle(ResponseInterface $response): TemperatureResult;
}
