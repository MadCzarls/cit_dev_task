<?php

declare(strict_types=1);

namespace App\RequestApi\Http;

class Response implements ResponseInterface
{
    private int $statusCode;
    private ?string $body;

    public function __construct(int $statusCode, ?string $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
}
