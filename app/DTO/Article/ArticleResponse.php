<?php

namespace App\DTO\Article;

final readonly class ArticleResponse
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public string $authorName,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
