<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use App\Exception\UnauthorizedException;

interface AuthenticatorInterface
{
    /**
     * @throws UnauthorizedException
     */
    public function authenticate(string $token): User;
}
