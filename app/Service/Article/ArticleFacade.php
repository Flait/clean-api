<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\DTO\Article\ArticleListResponse;
use App\DTO\Article\ArticleResponse;
use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;
use App\Entity\User;
use App\Enum\Action;
use App\Exception\NotFoundException;
use App\Repository\Article\ArticleRepositoryInterface;
use App\Service\Authorization\AuthorizationService;
use Symfony\Component\Serializer\SerializerInterface;

final class ArticleFacade implements ArticleFacadeInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepository,
        private AuthorizationService $authorizationService,
        private SerializerInterface $serializer,
    ) {
    }

    public function list(User $user): ArticleListResponse
    {
        $this->authorizationService->assertCan($user, Action::ARTICLE_LIST);

        $articles = $this->articleRepository->findAll();

        $response = array_map(
            fn (array $data) => $this->serializer->denormalize($data, ArticleResponse::class),
            $this->serializer->normalize($articles)
        );

        return new ArticleListResponse(
            articles: $response,
        );
    }

    public function detail(User $user, int $id): ArticleResponse
    {
        $this->authorizationService->assertCan($user, Action::ARTICLE_DETAIL);

        $article = $this->articleRepository->findById($id);

        if (!$article) {
            throw new NotFoundException('Article not found', 404);
        }


        return $this->serializer->denormalize(
            $this->serializer->normalize($article),
            ArticleResponse::class
        );
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

        $this->articleRepository->update($article, $data);
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
