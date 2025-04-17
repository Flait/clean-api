<?php

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;

class AdminAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function canAccess(User $user, Action $action, ?int $resourceOwnerId = null): bool
    {
        return true; // Admin smí vše
    }
}
