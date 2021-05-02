<?php

declare(strict_types=1);

namespace App\RequestApi\Http;

use App\Enum\RequestHttpMethodEnum;

class Request implements RequestInterface
{
    private RequestHttpMethodEnum $httpMethodEnum;
    private string $url;
    /** @var Header[] */
    private array $headers;

    /**
     * @param Header[] $headers
     */
    public function __construct(RequestHttpMethodEnum $httpMethodEnum, string $url, array $headers)
    {
        $this->httpMethodEnum = $httpMethodEnum;
        $this->url = $url;
        $this->headers = $headers;
    }

    public function getHttpMethodEnum(): RequestHttpMethodEnum
    {
        return $this->httpMethodEnum;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
