<?php

namespace App\Repository\Article;

use App\Entity\Article;

interface ArticleRepositoryInterface
{
    public function findById(int $id): ?Article;

    /**
     * @return Article[]
     */
    public function findAll(): array;

    public function save(Article $article): void;

    public function delete(Article $article): void;
}
