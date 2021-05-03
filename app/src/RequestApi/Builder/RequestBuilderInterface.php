<?php

declare(strict_types=1);

namespace App\RequestApi\Builder;

use App\Enum\RequestHttpMethodEnum;
use App\RequestApi\Http\RequestInterface;

interface RequestBuilderInterface
{
    public function setHttpMethod(RequestHttpMethodEnum $method): self;

    public function addHeader(string $name, string $value): self;

    public function setUrl(string $url): self;

    public function build(): RequestInterface;
    
    public function reset(): void;
}
