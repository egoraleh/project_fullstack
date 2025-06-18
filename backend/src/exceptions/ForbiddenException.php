<?php

namespace app\exceptions;

class ForbiddenException extends HttpException
{
    public function __construct(string $message = "Доступ запрещен")
    {
        parent::__construct($message, 403);
    }
}