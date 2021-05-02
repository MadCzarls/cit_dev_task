<?php

declare(strict_types=1);

namespace App\RequestApi\Client;

use App\RequestApi\Http\RequestInterface;
use App\RequestApi\Http\ResponseInterface;

interface ClientInterface
{
    public function execute(RequestInterface $request): ResponseInterface;
}
