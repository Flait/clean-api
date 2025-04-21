<?php

declare(strict_types=1);

namespace App\Presenter;

use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Response\SimpleResponse;
use App\Service\Article\ArticleFacadeInterface;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class ArticlePresenter extends BasePresenter
{
    #[Inject]
    public ArticleFacadeInterface $articleFacade;

    public function actionList(): void
    {
        $user = $this->getUserEntity();
        $articles = $this->articleFacade->list($user);

        $this->sendResponse(new JsonResponse($articles));
    }

    public function actionDetail(int $id): void
    {
        $user = $this->getUserEntity();
        $article = $this->articleFacade->detail($user, $id);

        $this->sendResponse(new JsonResponse($article));
    }

    public function actionCreate(): void
    {
        $user = $this->getUserEntity();
        /** @var CreateArticleData $dto */
        $dto = $this->createDto(CreateArticleData::class, $this->parseJsonBody());

        $this->articleFacade->create($user, $dto);

        $this->sendResponse(new SimpleResponse(201, 'Article created successfully.'));
    }

    public function actionUpdate(int $id): void
    {
        $user = $this->getUserEntity();
        /** @var UpdateArticleData $dto */
        $dto = $this->createDto(UpdateArticleData::class, $this->parseJsonBody());

        $this->articleFacade->update($user, $id, $dto);

        $this->sendResponse(new SimpleResponse(200, 'Article updated.'));
    }

    public function actionDelete(int $id): void
    {
        $user = $this->getUserEntity();
        $this->articleFacade->delete($user, $id);

        $this->sendResponse(new SimpleResponse(200, 'Article deleted.'));
    }
}
