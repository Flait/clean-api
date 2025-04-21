<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;
use Nette\Caching\Cache;

final class CachedArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private ArticleRepositoryInterface $inner,
        private Cache $cache,
        private string $articleExpire = '10 minutes'
    ) {
    }

    private function options(): array
    {
        return [
            Cache::Expire => $this->articleExpire,
        ];
    }

    public function findById(int $id): ?Article
    {
        return $this->cache->load("article:$id", fn () => $this->inner->findById($id), $this->options());
    }

    public function findAll(): array
    {
        return $this->cache->load('article:list', fn () => $this->inner->findAll(), $this->options());
    }

    public function insert(Article $article): void
    {
        $this->inner->insert($article);
        $this->invalidate();
    }

    public function update(Article $article, UpdateArticleData $data): void
    {
        $this->inner->update($article, $data);
        $this->invalidate($article);
    }

    public function delete(Article $article): void
    {
        $this->inner->delete($article);
        $this->invalidate($article);
    }

    private function invalidate(Article $article = null): void
    {
        if ($article !== null) {
            $this->cache->remove("article:{$article->getId()}");
        }
        $this->cache->remove('article:list');
    }
}
