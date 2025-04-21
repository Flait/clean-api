<?php

declare(strict_types=1);

namespace App\Exception;

final class NotFoundException extends ApiException
{
    public function __construct(string $message = 'Forbidden', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
