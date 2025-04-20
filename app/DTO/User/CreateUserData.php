<?php

namespace App\DTO\User;

use App\Enum\Role;

final readonly class CreateUserData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $name,
        public readonly Role $role,
    ) {
    }
}
