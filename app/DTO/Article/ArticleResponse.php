<?php

declare(strict_types=1);

namespace App\DTO\Article;

final class ArticleResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $content,
        public readonly AuthorResponse $author,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }
}
