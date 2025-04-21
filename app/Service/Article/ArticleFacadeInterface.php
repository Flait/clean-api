<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\DTO\Article\ArticleListResponse;
use App\DTO\Article\ArticleResponse;
use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Entity\User;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;

interface ArticleFacadeInterface
{
    /**
     * Returns list of articles as DTOs.
     */
    public function list(User $user): ArticleListResponse;

    /**
     * Returns detail of an article as DTO.
     *
     * @throws NotFoundException
     */
    public function detail(User $user, int $id): ArticleResponse;

    /**
     * Creates a new article.
     *
     * @throws ForbiddenException
     */
    public function create(User $user, CreateArticleData $data): void;

    /**
     * Updates an existing article.
     *
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function update(User $user, int $id, UpdateArticleData $data): void;

    /**
     * Deletes an article.
     *
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function delete(User $user, int $id): void;
}
