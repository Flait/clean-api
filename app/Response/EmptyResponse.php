<?php

namespace App\Response;

use Nette\Application\Response;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

final class EmptyResponse implements Response
{
    public function __construct(
        private int $statusCode = IResponse::S204_NoContent,
    ) {
    }

    public function send(IRequest $httpRequest, IResponse $httpResponse): void
    {
        $httpResponse->setCode($this->statusCode);
    }
}
