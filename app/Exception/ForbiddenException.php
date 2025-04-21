<?php

declare(strict_types=1);

namespace App\Exception;

final class ForbiddenException extends ApiException
{
    public function __construct(string $message = 'Forbidden', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
