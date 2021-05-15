<?php

declare(strict_types=1);

namespace App\Weather;

use App\Cache\CacheHandler;
use App\DTO\Weather\CountryCity;
use App\DTO\Weather\TemperatureHandlerResult;
use App\Exception\TemperatureNotCalculatedException;

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

    public function getTemperature(CountryCity $countryCity): TemperatureHandlerResult
    {
        $cacheKey = $this->generateCacheKey($countryCity->getCountry() . $countryCity->getCity());
        $cacheItem = $this->cacheHandler->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return new TemperatureHandlerResult(
                $cacheItem->get(),
                $countryCity->getCountry(),
                $countryCity->getCity(),
                true
            );
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
        $this->cacheHandler->save($cacheItem);

        return new TemperatureHandlerResult(
            //may also persist API identifiers from results (available from $result->getApiIdentifier())
            $temperature,
            $countryCity->getCountry(),
            $countryCity->getCity()
        );
    }

    private function generateCacheKey(string $base): string
    {
        return md5(strtolower($base));
    }
}
