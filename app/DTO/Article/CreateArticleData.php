<?php

namespace App\DTO\Article;

final readonly class CreateArticleData
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
    ) {
    }
}
