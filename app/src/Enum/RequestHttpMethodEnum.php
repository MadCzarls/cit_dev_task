<?php

declare(strict_types=1);

namespace App\Enum;

use MyCLabs\Enum\Enum;

final class RequestHttpMethodEnum extends Enum
{
    public const POST = 'POST';
    public const GET = 'GET';
}
