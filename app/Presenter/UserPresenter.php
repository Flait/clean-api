<?php

declare(strict_types=1);

namespace App\Presenter;

use App\DTO\User\CreateUserData;
use App\DTO\User\UpdateUserData;
use App\Response\SimpleResponse;
use App\Service\User\UserFacade;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class UserPresenter extends BasePresenter
{
    #[Inject]
    public UserFacade $userFacade;

    public function actionList(): void
    {
        $actor = $this->getUserEntity();

        $response = $this->userFacade->list($actor);
        $this->sendResponse(new JsonResponse($response));
    }

    public function actionDetail(int $id): void
    {
        $actor = $this->getUserEntity();
        $response = $this->userFacade->detail($actor, $id);
        $this->sendResponse(new JsonResponse($response));
    }

    public function actionCreate(): void
    {
        $actor = $this->getUserEntity();
        $dto = $this->createDto(CreateUserData::class, $this->parseJsonBody());

        $this->userFacade->create($actor, $dto);

        $this->sendResponse(new SimpleResponse(201, 'User created'));
    }

    public function actionUpdate(int $id): void
    {
        $actor = $this->getUserEntity();
        $dto = $this->createDto(UpdateUserData::class, $this->parseJsonBody());

        $this->userFacade->update($actor, $id, $dto);

        $this->sendResponse(new SimpleResponse(200, 'User updated'));
    }

    public function actionDelete(int $id): void
    {
        $actor = $this->getUserEntity();
        $this->userFacade->delete($actor, $id);

        $this->sendResponse(new SimpleResponse(200, 'User deleted'));
    }
}
