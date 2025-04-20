<?php

namespace App\Service\User;

use App\DTO\User\CreateUserData;
use App\DTO\User\UpdateUserData;
use App\DTO\User\UserListResponse;
use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Enum\Action;
use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use App\Service\Authorization\AuthorizationService;

final class UserFacade
{
    public function __construct(
        private UserRepository $userRepository,
        private AuthorizationService $authorizationService,
    ) {
    }

    public function list(User $actor): UserListResponse
    {
        $this->authorizationService->assertCan($actor, Action::USER_LIST);

        $users = $this->userRepository->findAll();

        $responses = array_map(
            fn (User $user) => new UserResponse(
                id: $user->getId(),
                email: $user->getEmail(),
                name: $user->getName(),
                role: $user->getRole()->value,
            ),
            $users,
        );

        return new UserListResponse(
            users: $responses,
            page: 1,
            perPage: count($responses),
            total: count($responses),
        );
    }

    public function detail(User $actor, int $id): UserResponse
    {
        $this->authorizationService->assertCan($actor, Action::USER_DETAIL);

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundException('User not found', 404);
        }

        return new UserResponse(
            id: $user->getId(),
            email: $user->getEmail(),
            name: $user->getName(),
            role: $user->getRole()->value,
        );
    }

    public function create(User $actor, CreateUserData $data): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_CREATE);

        $user = new User(
            email: $data->email,
            passwordHash: $data->password,
            name: $data->name,
            role: $data->role,
        );

        $this->userRepository->save($user);
    }

    public function update(User $actor, int $id, UpdateUserData $data): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_UPDATE);

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundException('User not found', 404);
        }

        // Explicit updates:
        $user->setEmail($data->email);
        $user->setName($data->name);
        $user->setRole($data->role);

        $this->userRepository->save($user);
    }

    public function delete(User $actor, int $id): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_DELETE);

        $this->userRepository->delete($id);
    }
}
