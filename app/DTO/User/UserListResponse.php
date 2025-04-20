<?php

namespace App\DTO\User;

final readonly class UserListResponse
{
    /** @param UserResponse[] $users */
    public function __construct(
        public array $users,
        public int $page,
        public int $perPage,
        public int $total,
    ) {
    }
}
