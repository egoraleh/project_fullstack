<?php

declare(strict_types=1);

namespace app\core;

use app\enums\HttpStatusCodeEnum;

class Response
{
    public function setStatusCode(HttpStatusCodeEnum $code): void
    {
        http_response_code($code->value);
    }

    public function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
