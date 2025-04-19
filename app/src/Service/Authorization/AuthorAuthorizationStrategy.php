<?php

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;

class AuthorAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function canAccess(User $user, Action $action, ?int $resourceOwnerId = null): bool
    {
        return match ($action) {
            Action::CREATE_ARTICLE => true,
            Action::UPDATE_OWN_ARTICLE,
            Action::DELETE_ARTICLE => $user->getId() === $resourceOwnerId,
            default                => false,
        };
    }
}
