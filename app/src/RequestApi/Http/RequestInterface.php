<?php

declare(strict_types=1);

namespace App\RequestApi\Http;

use App\Enum\RequestHttpMethodEnum;

interface RequestInterface
{
    public function getHttpMethodEnum(): RequestHttpMethodEnum;

    public function getUrl(): string;

    /**
     * @return Header[]
     */
    public function getHeaders(): array;
}
