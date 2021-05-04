<?php

declare(strict_types=1);

namespace App\Weather;

use App\Cache\CacheHandler;
use App\DTO\Weather\CountryCity;
use App\Exception\TemperatureNotCalculatedException;

use function dd;
use function md5;
use function strtolower;

class TemperatureHandler
{
    private CacheHandler $cacheHandler;
    private ApiHandler $apiHandler;
    private TemperatureCalculator $temperatureCalculator;

    public function __construct(
        CacheHandler $cacheHandler,
        ApiHandler $apiHandler,
        TemperatureCalculator $temperatureCalculator
    ) {
        $this->cacheHandler = $cacheHandler;
        $this->apiHandler = $apiHandler;
        $this->temperatureCalculator = $temperatureCalculator;
    }

    public function getTemperature(CountryCity $countryCity): float
    {
        $cacheKey = $this->generateCacheKey($countryCity->getCountry() . $countryCity->getCity());
        $cacheItem = $this->cacheHandler->getCache()->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $apiResults = $this->apiHandler->getResults($countryCity);

        if (empty($apiResults)) {
            throw new TemperatureNotCalculatedException();
        }

        foreach ($apiResults as $result) {
            $this->temperatureCalculator->add($result);
        }

        $temperature = $this->temperatureCalculator->calculate();

        $cacheItem->set($temperature);
        $this->cacheHandler->getCache()->save($cacheItem);

        return $temperature;
    }

    private function generateCacheKey(string $base): string
    {
        return md5(strtolower($base));
    }
}
