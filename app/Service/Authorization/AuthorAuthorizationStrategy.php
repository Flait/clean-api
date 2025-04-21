<?php

declare(strict_types=1);

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;

class AuthorAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function canAccess(User $user, Action $action, ?int $resourceOwnerId = null): bool
    {
        return match ($action) {
            Action::ARTICLE_CREATE => true,
            Action::ARTICLE_UPDATE,
            Action::ARTICLE_DELETE => $user->getId() === $resourceOwnerId,
            Action::ARTICLE_LIST,
            Action::ARTICLE_DETAIL => true,
            default                => false,
        };
    }
}
