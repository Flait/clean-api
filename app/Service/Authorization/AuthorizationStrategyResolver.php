<?php

declare(strict_types=1);

namespace App\Service\Authorization;

use App\Entity\User;
use RuntimeException;

class AuthorizationStrategyResolver
{
    /**
     * @param array<string, AuthorizationStrategyInterface> $strategies
     */
    public function __construct(
        readonly private array $strategies,
    ) {
    }

    public function resolve(User $user): AuthorizationStrategyInterface
    {
        $role = $user->getRole()->value;

        if (!isset($this->strategies[$role])) {
            throw new RuntimeException("No strategy found for role: $role");
        }

        return $this->strategies[$role];
    }
}
