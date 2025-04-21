<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\Enum\Role;

final class UserResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly Role $role,
    ) {
    }
}
