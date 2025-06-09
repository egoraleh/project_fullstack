<?php
declare(strict_types=1);

namespace app\exceptions;

use Exception;

/**
 * Исключение, выбрасываемое при ошибках валидации данных.
 * Контроллеры могут ловить его и возвращать клиенту 400 Bad Request.
 */
class ValidationException extends Exception
{
    /**
     * Конструктор ValidationException.
     *
     * @param string          $message  Описание ошибки валидации
     * @param int             $code     Код ошибки (по умолчанию 0)
     * @param Exception|null  $previous Предыдущее исключение
     */
    public function __construct(
        string $message = 'Validation failed',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
