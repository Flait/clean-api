<?php

namespace App\Presenter;

use App\Entity\User;
use App\Exception\ForbiddenException;
use Nette\Application\UI\Presenter;
use Nette\Http\IRequest;
use Nette\Utils\Json;

abstract class BasePresenter extends Presenter
{
    public function __construct(
        protected IRequest $httpRequest,
    ) {
        parent::__construct();
    }

    protected function getUserEntity(): User
    {
        $identity = $this->getUser()->getIdentity();
        if (!$identity instanceof User) {
            throw new ForbiddenException('User not authenticated');
        }

        return $identity;
    }

    protected function parseJsonBody(): array
    {
        $raw = $this->httpRequest->getRawBody();
        $data = Json::decode($raw, true);

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON payload');
        }

        return $data;
    }
}
