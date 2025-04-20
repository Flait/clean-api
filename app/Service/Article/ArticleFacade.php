<?php

namespace App\Service\Article;

use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;
use App\Entity\User;
use App\Enum\Action;
use App\Exception\NotFoundException;
use App\Repository\Article\ArticleRepositoryInterface;
use App\Service\Authorization\AuthorizationService;

final class ArticleFacade implements ArticleFacadeInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private AuthorizationService $authorizationService,
    ) {
    }

    /** @return Article[] */
    public function list(User $user): array
    {
        $this->authorizationService->assertCan($user, Action::ARTICLE_LIST);
        return $this->articleRepository->findAll();
    }

    public function detail(User $user, int $id): Article
    {
        $this->authorizationService->assertCan($user, Action::ARTICLE_DETAIL);

        $article = $this->articleRepository->findById($id);

        if (!$article) {
            throw new NotFoundException('Article not found', 404);
        }

        return $article;
    }

    public function create(User $user, CreateArticleData $data): void
    {
        $this->authorizationService->assertCan($user, Action::ARTICLE_CREATE);

        $article = new Article(
            title: $data->title,
            content: $data->content,
            author: $user,
        );

        $this->articleRepository->insert($article);
    }

    public function update(User $user, int $id, UpdateArticleData $data): void
    {
        $article = $this->articleRepository->findById($id);

        if (!$article) {
            throw new NotFoundException('Article not found', 404);
        }

        $this->authorizationService->assertCan($user, Action::ARTICLE_UPDATE, $article->getAuthorId());

        $article->setTitle($data->title);
        $article->setContent($data->content);

        $this->articleRepository->update($article);
    }

    public function delete(User $user, int $id): void
    {
        $article = $this->articleRepository->findById($id);

        if (!$article) {
            throw new NotFoundException('Article not found', 404);
        }

        $this->authorizationService->assertCan($user, Action::ARTICLE_DELETE, $article->getAuthorId());

        $this->articleRepository->delete($article);
    }
}
