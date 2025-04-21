<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;

interface ArticleRepositoryInterface
{
    public function findById(int $id): ?Article;

    /**
     * @return Article[]
     */
    public function findAll(): array;

    public function insert(Article $article): void;

    public function update(Article $article, UpdateArticleData $data): void;

    public function delete(Article $article): void;
}
