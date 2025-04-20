<?php

namespace App\Router;

use Nette\Application\Routers\Route;
use Nette\Http\IRequest;

final class MethodRestrictedRoute extends Route
{
    /** @var string[] */
    private array $methods;

    /**
     * @param array<string> $methods
     */
    public function __construct(string $mask, array $metadata, array $methods)
    {
        parent::__construct($mask, $metadata);
        $this->methods = array_map('strtoupper', $methods);
    }

    public static function from(string $mask, string $target, array $methods): self
    {
        [$presenter, $action] = explode(':', $target);

        return new self($mask, [
            'presenter' => $presenter,
            'action'    => $action,
        ], $methods);
    }

    public function match(IRequest $httpRequest): ?array
    {
        if (!in_array($httpRequest->getMethod(), $this->methods, true)) {
            return null;
        }

        return parent::match($httpRequest);
    }
}
