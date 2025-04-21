<?php

declare(strict_types=1);

namespace App\Exception;

final class InvalidCredentialsException extends ApiException
{
    public function __construct(string $message = 'Invalid credentials', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
