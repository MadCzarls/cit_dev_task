<?php

declare(strict_types=1);

namespace App\DTO\Weather;

class TemperatureResult
{
    private string $apiIdentifier;
    private float $temperature;

    public function __construct(string $apiIdentifier, float $temperature)
    {
        $this->apiIdentifier = $apiIdentifier;
        $this->temperature = $temperature;
    }

    public function getApiIdentifier(): string
    {
        return $this->apiIdentifier;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }
}
