<?php

namespace app\exceptions;

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = "Не авторизован")
    {
        parent::__construct($message, 401);
    }
}