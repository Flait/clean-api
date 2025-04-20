<?php

namespace App\Presenter;

use App\DTO\User\CreateUserData;
use App\Entity\User;
use App\Enum\Role;
use App\Exception\ForbiddenException;
use App\Repository\UserRepositoryInterface;
use App\Response\EmptyResponse;
use Firebase\JWT\JWT;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class AuthPresenter extends BasePresenter
{
    #[Inject]
    public UserRepositoryInterface $userRepository;

    public function actionRegister(): EmptyResponse
    {
        $data = $this->parseJsonBody();

        $dto = new CreateUserData(
            email: $data['email'],
            password: $data['password'],
            name: $data['name'],
            role: Role::from($data['role']),
        );

        $user = new User(
            email: $dto->email,
            passwordHash: $dto->password,
            name: $dto->name,
            role: $dto->role,
        );

        $this->userRepository->save($user);

        return new EmptyResponse(201);
    }

    public function actionLogin(): JsonResponse
    {
        $data = $this->parseJsonBody();

        $user = $this->userRepository->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->getPasswordHash())) {
            throw new ForbiddenException('Invalid credentials');
        }

        $payload = [
            'sub'  => $user->getEmail(),
            'role' => $user->getRole()->value,
            'iat'  => time(),
            'exp'  => time() + 3600,
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return new JsonResponse(['token' => $jwt]);
    }
}
