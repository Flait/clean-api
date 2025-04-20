<?php

namespace App\Presenter;

use App\DTO\User\CreateUserData;
use App\DTO\User\UpdateUserData;
use App\Response\EmptyResponse;
use App\Service\User\UserFacade;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class UserPresenter extends BasePresenter
{
    #[Inject]
    public UserFacade $userFacade;

    public function actionList(): JsonResponse
    {
        $actor = $this->getUserEntity();
        return new JsonResponse($this->userFacade->list($actor));
    }

    public function actionDetail(int $id): JsonResponse
    {
        $actor = $this->getUserEntity();
        return new JsonResponse($this->userFacade->detail($actor, $id));
    }

    public function actionCreate(): EmptyResponse
    {
        $actor = $this->getUserEntity();
        $data = $this->parseJsonBody();

        $dto = new CreateUserData(
            email: $data['email'],
            password: $data['password'],
            name: $data['name'],
            role: \App\Enum\Role::from($data['role'])
        );

        $this->userFacade->create($actor, $dto);

        return new EmptyResponse(201);
    }

    public function actionUpdate(int $id): EmptyResponse
    {
        $actor = $this->getUserEntity();
        $data = $this->parseJsonBody();

        $dto = new UpdateUserData(
            email: $data['email'],
            name: $data['name'],
            role: \App\Enum\Role::from($data['role'])
        );

        $this->userFacade->update($actor, $id, $dto);

        return new EmptyResponse();
    }

    public function actionDelete(int $id): EmptyResponse
    {
        $actor = $this->getUserEntity();
        $this->userFacade->delete($actor, $id);

        return new EmptyResponse();
    }
}
