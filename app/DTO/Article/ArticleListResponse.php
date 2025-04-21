<?php

declare(strict_types=1);

namespace App\DTO\Article;

final class ArticleListResponse
{
    /**
     * @param ArticleResponse[] $articles
     */
    public function __construct(
        public readonly array $articles
    ) {
    }
}
