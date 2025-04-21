<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use App\Exception\UnauthorizedException;
use App\Repository\UserRepositoryInterface;
use App\Service\Auth\Token\JwtTokenService;

final class JwtAuthenticator implements AuthenticatorInterface
{
    public function __construct(
        private JwtTokenService $tokenService,
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function authenticate(string $token): User
    {
        $payload = $this->tokenService->decode($token);
        if ($payload === null) {
            throw new UnauthorizedException('Token invalid.');
        }

        if (empty($payload['sub'])) {
            throw new UnauthorizedException('Token missing subject.');
        }

        $user = $this->userRepository->findByEmail($payload['sub']);
        if (!$user) {
            throw new UnauthorizedException('User not found.');
        }

        return $user;
    }
}
