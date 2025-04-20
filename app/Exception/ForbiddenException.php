<?php

namespace App\Exception;

final class ForbiddenException extends \RuntimeException
{
    public function __construct(string $message = 'Forbidden', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
