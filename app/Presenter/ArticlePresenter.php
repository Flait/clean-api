<?php

namespace App\Presenter;

use App\DTO\Article\CreateArticleData;
use App\DTO\Article\UpdateArticleData;
use App\Response\EmptyResponse;
use App\Service\Article\ArticleFacadeInterface;
use Nette\Application\Responses\JsonResponse;
use Nette\DI\Attributes\Inject;

final class ArticlePresenter extends BasePresenter
{
    #[Inject]
    public ArticleFacadeInterface $articleFacade;

    public function actionList(): JsonResponse
    {
        $user = $this->getUserEntity();
        $articles = $this->articleFacade->list($user);

        return new JsonResponse($articles);
    }

    public function actionDetail(int $id): JsonResponse
    {
        $user = $this->getUserEntity();
        $article = $this->articleFacade->detail($user, $id);

        return new JsonResponse($article);
    }

    public function actionCreate(): EmptyResponse
    {
        $user = $this->getUserEntity();
        $data = $this->parseJsonBody();
        $dto = new CreateArticleData($data['title'], $data['content']);

        $this->articleFacade->create($user, $dto);

        return new EmptyResponse(201);
    }

    public function actionUpdate(int $id): EmptyResponse
    {
        $user = $this->getUserEntity();
        $data = $this->parseJsonBody();
        $dto = new UpdateArticleData($data['title'], $data['content']);

        $this->articleFacade->update($user, $id, $dto);

        return new EmptyResponse();
    }

    public function actionDelete(int $id): EmptyResponse
    {
        $user = $this->getUserEntity();
        $this->articleFacade->delete($user, $id);

        return new EmptyResponse();
    }
}
