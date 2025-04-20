<?php

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;
use App\Exception\ForbiddenException;

final class AuthorizationService
{
    public function __construct(
        private AuthorizationStrategyResolver $resolver,
    ) {
    }

    private function check(User $user, Action $action, ?int $resourceOwnerId = null): bool
    {
        $strategy = $this->resolver->resolve($user);
        return $strategy->canAccess($user, $action, $resourceOwnerId);
    }

    public function assertCan(User $user, Action $action, ?int $resourceOwnerId = null): void
    {
        if (!$this->check($user, $action, $resourceOwnerId)) {
            throw new ForbiddenException("Access denied for action {$action->value}");
        }
    }
}
