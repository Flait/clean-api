<?php

declare(strict_types=1);

namespace App\DTO\Article;

final class UpdateArticleData
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $content,
    ) {
    }
}
