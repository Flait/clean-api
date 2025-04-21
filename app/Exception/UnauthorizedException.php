<?php

declare(strict_types=1);

namespace App\Exception;

class UnauthorizedException extends ApiException
{
    public function __construct(string $message = 'Unauthorized')
    {
        parent::__construct($message, 401);
    }
}
