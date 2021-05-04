<?php

declare(strict_types=1);

namespace App\Weather;

use App\DTO\Weather\TemperatureResult;

use function count;
use function round;

class TemperatureCalculator
{
    /** @var TemperatureResult[] */
    private array $results = [];

    public function add(TemperatureResult $result): void
    {
        $this->results[] = $result;
    }

    public function calculate(): float
    {
        $resultsCount = count($this->results);

        if ($resultsCount <= 0) {
            return 0.0;
        }

        $temps = 0;
        foreach ($this->results as $result) {
            $temps += $result->getTemperature();
        }

        return round($temps / $resultsCount, 1);
    }
}
