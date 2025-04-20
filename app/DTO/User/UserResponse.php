<?php

namespace App\DTO\User;

final readonly class UserResponse
{
    public function __construct(
        public int $id,
        public string $email,
        public string $name,
        public string $role,
    ) {
    }
}
