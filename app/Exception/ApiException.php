<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

abstract class ApiException extends Exception
{
    public function __construct(string $message = '', public readonly int $statusCode = 400)
    {
        parent::__construct($message, $statusCode);
    }
}
