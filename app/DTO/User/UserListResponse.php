<?php

declare(strict_types=1);

namespace App\DTO\User;

final class UserListResponse
{
    /**
     * @param UserResponse[] $users
     */
    public function __construct(
        public readonly array $users
    ) {
    }
}
