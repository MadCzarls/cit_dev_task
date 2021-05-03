<?php

declare(strict_types=1);

namespace App\Weather;

use App\DTO\Weather\CountryCity;
use App\Exception\TemperatureNotCalculatedException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class TemperatureHandler
{
    //@TODO unitests

    private const CACHE_POOL = 'cache.app';
    private const CACHE_LIFETIME = 60;

    private FilesystemAdapter $cache;
    private ApiHandler $apiHandler;
    private TemperatureCalculator $temperatureCalculator;

    public function __construct(
        ApiHandler $apiHandler,
        TemperatureCalculator $temperatureCalculator
    ) {
        $this->cache = new FilesystemAdapter(self::CACHE_POOL, self::CACHE_LIFETIME);
        $this->apiHandler = $apiHandler;
        $this->temperatureCalculator = $temperatureCalculator;
    }

    public function getTemperature(CountryCity $countryCity): float
    {
        $cacheKey = $this->generateCacheKey($countryCity->getCountry() . $countryCity->getCity());
        $cacheItem = $this->cache->getItem($cacheKey);

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
        $this->cache->save($cacheItem);

        return $temperature;
    }

    private function generateCacheKey(string $base): string
    {
        return md5(strtolower($base));
    }
}
