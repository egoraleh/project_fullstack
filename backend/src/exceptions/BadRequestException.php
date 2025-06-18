<?php

namespace app\exceptions;

class BadRequestException extends HttpException
{
    public function __construct(string $message = "Некорректный запрос")
    {
        parent::__construct($message, 400);
    }
}