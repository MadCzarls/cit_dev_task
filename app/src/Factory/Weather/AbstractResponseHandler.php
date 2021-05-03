<?php

declare(strict_types=1);

namespace App\Factory\Weather;

abstract class AbstractResponseHandler implements ResponseHandlerInterface
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}