<?php

declare(strict_types=1);

namespace App\Exception;

final class UserAlreadyExistsException extends ApiException
{
    public function __construct(string $email)
    {
        parent::__construct("User with email {$email} already exists.", 409);
    }
}
