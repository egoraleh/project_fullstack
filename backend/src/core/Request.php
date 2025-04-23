<?php

declare(strict_types=1);

namespace app\core;

use app\enums\MethodEnum;

class Request
{
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        return rtrim($path, '/') ?: '/';
    }

    public function getMethod(): MethodEnum
    {
        return MethodEnum::from(strtoupper($_SERVER['REQUEST_METHOD']));
    }

    public function getBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
