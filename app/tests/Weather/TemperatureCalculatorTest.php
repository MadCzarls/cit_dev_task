<?php

declare(strict_types=1);

namespace App\Tests\Weather;

use App\DTO\Weather\TemperatureResult;
use App\Weather\TemperatureCalculator;
use PHPUnit\Framework\TestCase;

class TemperatureCalculatorTest extends TestCase
{
    /**
     * @param TemperatureResult[] $temperatureResults
     *
     * @dataProvider dataProvider
     */
    public function testCalculate(array $temperatureResults, float $expectedTemperature): void
    {
        $temperatureCalculator = new TemperatureCalculator();

        foreach ($temperatureResults as $result) {
            $temperatureCalculator->add($result);
        }

        $this->assertEquals($expectedTemperature, $temperatureCalculator->calculate());
    }

    /**
     * @return TemperatureResult[]
     */
    public function dataProvider(): array
    {
        return [
            'none temperature results should return 0.0' => [
                [],
                0.0,
            ],
            'rounding with precision 1' => [
                [
                    new TemperatureResult('test', 12.48),
                    new TemperatureResult('test', -7.33),
                ],
                2.6,
            ],
            '15.3' => [
                [
                    new TemperatureResult('test', 15.6),
                    new TemperatureResult('test', 15.2),
                    new TemperatureResult('test', 15.1),
                ],
                15.3,
            ],
            '22.6' => [
                [
                    new TemperatureResult('test', 22.6),
                ],
                22.6,
            ],
            '-7.5' => [
                [
                    new TemperatureResult('test', -8.0),
                    new TemperatureResult('test', -7.0),
                ],
                -7.5,
            ],
        ];
    }
}
