<?php

namespace App\DTO\Article;

final readonly class ArticleListResponse
{
    /** @param ArticleResponse[] $articles */
    public function __construct(
        public array $articles,
        public int $page,
        public int $perPage,
        public int $total,
    ) {
    }
}
