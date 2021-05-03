<?php

declare(strict_types=1);

namespace App\Factory\Weather;

interface FactoryInterface
{
    public function getName(): string;
    
    public function createRequestHandler(): RequestHandlerInterface;

    public function createResponseHandler(): ResponseHandlerInterface;
}
