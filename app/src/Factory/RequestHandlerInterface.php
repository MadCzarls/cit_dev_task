<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\Weather\CountryCity;
use App\RequestApi\Builder\RequestBuilderInterface;

interface RequestHandlerInterface
{
    public function requestForCountryAndCity(CountryCity $countryCity): void;

    public function setBuilder(RequestBuilderInterface $builder): void;
}
