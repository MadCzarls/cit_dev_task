<?php

declare(strict_types=1);

namespace App\Tests\Weather;

use App\Cache\CacheHandler;
use App\DTO\Weather\CountryCity;
use App\DTO\Weather\TemperatureResult;
use App\Exception\TemperatureNotCalculatedException;
use App\Weather\ApiHandler;
use App\Weather\TemperatureCalculator;
use App\Weather\TemperatureHandler;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class TemperatureHandlerTest extends TestCase
{
    private Stub|ApiHandler $apiHandler;
    private Stub|TemperatureCalculator $temperatureCalculator;
    private TemperatureHandler $temperatureHandler;
    private Stub|FilesystemAdapter $cache;

    public function testGetTemperatureGetsResultFromCacheIfPresent(): void
    {
        $countryCity = $this->createStub(CountryCity::class);
        $item = $this->createMock(ItemInterface::class);
        $this->cache->method('getItem')->willReturn($item);

        $item->method('isHit')->willReturn(true);
        $item->expects($this->once())->method('get')->willReturn(1.0);

        $this->temperatureHandler->getTemperature($countryCity);
    }

    public function testGetTemperatureThrowsExceptionIfNoApiResults(): void
    {
        $countryCity = $this->createStub(CountryCity::class);
        $this->apiHandler->method('getResults')->willReturn([]);
        $item = $this->createStub(ItemInterface::class);
        $this->cache->method('getItem')->willReturn($item);

        $this->expectException(TemperatureNotCalculatedException::class);
        $this->temperatureHandler->getTemperature($countryCity);
    }

    public function testGetTemperatureSavesInCache(): void
    {
        $countryCity = $this->createStub(CountryCity::class);
        $apiResult = $this->createStub(TemperatureResult::class);
        $this->apiHandler->method('getResults')->willReturn([$apiResult]);
        $item = $this->createStub(ItemInterface::class);
        $this->cache->method('getItem')->willReturn($item);
        $this->temperatureCalculator->method('calculate')->willReturn(7.2);

        $item->expects($this->once())->method('set');
        $this->cache->expects($this->once())->method('save');

        $this->temperatureHandler->getTemperature($countryCity);
    }

    protected function setUp(): void
    {
        $this->cache = $this->createStub(FilesystemAdapter::class);
        $cacheHandler = $this->createStub(CacheHandler::class);
        $cacheHandler->method('getCache')->willReturn($this->cache);

        $this->apiHandler = $this->createStub(ApiHandler::class);
        $this->temperatureCalculator = $this->createStub(TemperatureCalculator::class);
        $this->temperatureHandler = new TemperatureHandler(
            $cacheHandler,
            $this->apiHandler,
            $this->temperatureCalculator
        );
    }
}
