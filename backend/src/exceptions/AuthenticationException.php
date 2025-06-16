<?php
declare(strict_types=1);

namespace app\exceptions;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct(
        string $message = 'Authentication failed',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}