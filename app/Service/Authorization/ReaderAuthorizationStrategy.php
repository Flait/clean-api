<?php

declare(strict_types=1);

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;

class ReaderAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function canAccess(User $user, Action $action, ?int $resourceOwnerId = null): bool
    {
        return in_array($action, [
            Action::ARTICLE_LIST,
            Action::ARTICLE_DETAIL,
        ], true);
    }
}
