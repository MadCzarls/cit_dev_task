<?php

declare(strict_types=1);

namespace App\Factory\Weather;

use App\DTO\Weather\CountryCity;
use App\RequestApi\Builder\RequestBuilderInterface;

interface RequestHandlerInterface
{
    public function prepareRequestForCountryAndCity(CountryCity $countryCity): void;

    public function setBuilder(RequestBuilderInterface $builder): void;
}
