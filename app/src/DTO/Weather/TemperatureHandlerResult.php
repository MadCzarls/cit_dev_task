<?php

declare(strict_types=1);

namespace App\DTO\Weather;

class TemperatureHandlerResult
{
    private float $temperature;
    private string $country;
    private string $city;
    private bool $isFromCache;

    public function __construct(float $temperature, string $country, string $city, bool $isFromCache = false)
    {
        $this->temperature = $temperature;
        $this->country = $country;
        $this->city = $city;
        $this->isFromCache = $isFromCache;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function isFromCache(): bool
    {
        return $this->isFromCache;
    }
}
