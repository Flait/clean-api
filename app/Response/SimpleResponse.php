<?php

declare(strict_types=1);

namespace App\Response;

use Nette\Application\Response;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

final class SimpleResponse implements Response
{
    public function __construct(
        private int $statusCode = IResponse::S204_NoContent,
        private ?string $message = null,
    ) {
    }

    public function send(IRequest $httpRequest, IResponse $httpResponse): void
    {
        $httpResponse->setCode($this->statusCode);
        $httpResponse->setContentType('application/json');

        echo json_encode([
            'code'    => $this->statusCode,
            'message' => $this->message,
        ], JSON_UNESCAPED_UNICODE);
    }
}
