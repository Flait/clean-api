<?php

declare(strict_types=1);

namespace App\Presenter;

use App\DTO\Auth\LoginData;
use App\DTO\Auth\RegisterUserData;
use App\Response\SimpleResponse;
use App\Service\Auth\AuthService;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class AuthPresenter extends BasePresenter
{
    #[Inject]
    public AuthService $authService;

    public function actionRegister(): void
    {
        $dto = $this->createDto(RegisterUserData::class, $this->parseJsonBody());
        $this->authService->register($dto);
        $this->sendResponse(new SimpleResponse(201, 'User registered successfully'));
    }

    public function actionLogin(): JsonResponse
    {
        $dto = $this->createDto(LoginData::class, $this->parseJsonBody());
        $token = $this->authService->login($dto->email, $dto->password);

        return $this->sendResponse(new JsonResponse(['token' => $token]));
    }
}
