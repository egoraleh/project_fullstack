<?php

declare(strict_types=1);

namespace app\enums;

enum MethodEnum: string
{
    case GET = "GET";
    case POST = "POST";
    case PUT = "PUT";
    case DELETE = "DELETE";
}
