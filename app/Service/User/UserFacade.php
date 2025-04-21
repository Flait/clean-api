<?php

declare(strict_types=1);

namespace App\Service\User;

use App\DTO\User\CreateUserData;
use App\DTO\User\UpdateUserData;
use App\DTO\User\UserListResponse;
use App\DTO\User\UserResponse;
use App\Entity\User;
use App\Enum\Action;
use App\Exception\NotFoundException;
use App\Repository\UserRepository;
use App\Service\Auth\AuthService;
use App\Service\Authorization\AuthorizationService;
use Symfony\Component\Serializer\SerializerInterface;

final class UserFacade
{
    public function __construct(
        private UserRepository $userRepository,
        private AuthorizationService $authorizationService,
        private AuthService $authService,
        private SerializerInterface $serializer,
    ) {
    }

    public function list(User $actor): UserListResponse
    {
        $this->authorizationService->assertCan($actor, Action::USER_LIST);

        $users = $this->userRepository->findAll();

        $responses = array_map(
            fn (array $data) => $this->serializer->denormalize($data, UserResponse::class),
            $this->serializer->normalize($users)
        );

        return new UserListResponse(
            users: $responses
        );
    }

    public function detail(User $actor, int $id): UserResponse
    {
        $this->authorizationService->assertCan($actor, Action::USER_DETAIL);

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundException('User not found', 404);
        }

        return $this->serializer->denormalize(
            $this->serializer->normalize($user),
            UserResponse::class
        );
    }

    public function create(User $actor, CreateUserData $data): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_UPDATE);
        $this->authService->register($data);
    }

    public function update(User $actor, int $id, UpdateUserData $data): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_UPDATE);

        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new NotFoundException('User not found', 404);
        }

        $this->userRepository->update($user, $data);
    }

    public function delete(User $actor, int $id): void
    {
        $this->authorizationService->assertCan($actor, Action::USER_DELETE);

        $this->userRepository->delete($id);
    }
}
