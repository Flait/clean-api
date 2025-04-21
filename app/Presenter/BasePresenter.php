<?php

declare(strict_types=1);

namespace App\Presenter;

use App\Entity\User as UserEntity;
use App\Exception\ApiException;
use App\Exception\InvalidCredentialsException;
use App\Service\UserContext;
use Exception;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;
use Nette\Http\IRequest;
use Nette\Utils\Json;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BasePresenter extends Presenter
{
    #[Inject]
    public SerializerInterface $serializer;

    #[Inject]
    public UserContext $userContext;

    public function __construct(
        protected IRequest $httpRequest,
    ) {
        parent::__construct();
    }

    public function run(Request $request): Response
    {
        try {
            return parent::run($request);
        } catch (ApiException $e) {
            return new JsonResponse([
                'code'    => $e->getCode(),
                'status'  => 'fail',
                'message' => $e->getMessage(),
            ], 'application/json');
        }
    }

    protected function getUserEntity(): UserEntity
    {
        return $this->userContext->getUser();
    }

    protected function parseJsonBody(): array
    {
        $raw = $this->httpRequest->getRawBody();
        if (empty(trim($raw))) {
            throw new InvalidCredentialsException('Empty request body.');
        }
        $data = Json::decode($raw, true);

        if (!is_array($data)) {
            throw new InvalidCredentialsException('Invalid JSON payload');
        }

        return $data;
    }

    protected function createDto(string $class, array $data): mixed
    {
        try {
            return $this->serializer->denormalize($data, $class);
        } catch (Exception $e) {
            throw new InvalidCredentialsException('Invalid input data for DTO.', 401);
        }
    }
}
