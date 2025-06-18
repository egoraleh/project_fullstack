<?php

namespace app\exceptions;

class NotFoundException extends HttpException
{
    public function __construct(string $message = "Ресурс не найден")
    {
        parent::__construct($message, 404);
    }
}