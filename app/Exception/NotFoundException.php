<?php

namespace App\Exception;

final class NotFoundException extends \RuntimeException
{
    public function __construct(string $message = 'Forbidden', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
