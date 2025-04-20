<?php

namespace App\Service\Article;

use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;
use App\Entity\User;
use App\Exception\ForbiddenException;
use App\Exception\NotFoundException;

interface ArticleFacadeInterface
{
    /**
     * @return Article[]
     */
    public function list(User $user): array;

    /**
     * @throws NotFoundException
     */
    public function detail(User $user, int $id): Article;

    /**
     * @throws ForbiddenException
     */
    public function create(User $user, CreateArticleData $data): void;

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function update(User $user, int $id, UpdateArticleData $data): void;

    /**
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function delete(User $user, int $id): void;
}
