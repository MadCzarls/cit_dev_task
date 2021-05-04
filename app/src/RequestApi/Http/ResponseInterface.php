<?php

declare(strict_types=1);

namespace App\RequestApi\Http;

interface ResponseInterface
{
    public function getStatusCode(): int;

    public function getBody(): ?string;
}
