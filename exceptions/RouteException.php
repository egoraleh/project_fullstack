<?php

declare(strict_types=1);

namespace app\exceptions;

use Throwable;

class RouteException extends \Exception
{
    private string $path;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, string $path = "")
    {
        parent::__construct($message, $code, $previous);
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
