<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\UnauthorizedException;
use App\Service\Auth\AuthenticatorInterface;

final class UserContext
{
    private ?User $user = null;

    public function __construct(
        private AuthenticatorInterface $authenticator,
        private \Nette\Http\IRequest $httpRequest,
    ) {
    }

    public function getUser(): User
    {
        if ($this->user) {
            return $this->user;
        }

        $header = $this->httpRequest->getHeader('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            throw new UnauthorizedException('Missing Authorization header.');
        }

        $token = substr($header, 7);

        $this->user = $this->authenticator->authenticate($token);

        return $this->user;
    }
}
