<?php

namespace App\Service;

use App\Exception\InvalidCredentialsException;
use App\Repository\UserRepositoryInterface;
use App\Service\Token\TokenServiceInterface;

final class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
    ) {
    }

    public function login(string $email, string $password): string
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new InvalidCredentialsException();
        }

        return $this->tokenService->encode($user);
    }
}
