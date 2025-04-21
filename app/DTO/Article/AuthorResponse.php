<?php

declare(strict_types=1);

namespace App\DTO\Article;

final class AuthorResponse
{
    public function __construct(
        public readonly string $name,
    ) {
    }
}
