<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\DTO\Auth\RegisterUserData;
use App\DTO\User\CreateUserData;
use App\Entity\User;
use App\Enum\Role;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepositoryInterface;
use App\Service\Auth\Token\TokenServiceInterface;

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

        if (!$user || !password_verify($password, $user->getPasswordHash())) {
            throw new InvalidCredentialsException();
        }

        return $this->tokenService->encode($user);
    }

    public function register(RegisterUserData|CreateUserData $dto): void
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new UserAlreadyExistsException($dto->email);
        }

        $user = new User(
            email: $dto->email,
            passwordHash: $dto->password,
            name: $dto->name,
            role: $dto->role ?? Role::READER
        );

        $this->userRepository->save($user);
    }
}
