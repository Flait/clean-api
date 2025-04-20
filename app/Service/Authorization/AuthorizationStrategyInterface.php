<?php

namespace App\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;

interface AuthorizationStrategyInterface
{
    public function canAccess(User $user, Action $action, ?int $resourceOwnerId = null): bool;
}
