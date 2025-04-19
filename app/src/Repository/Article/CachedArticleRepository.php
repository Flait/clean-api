<?php

namespace App\Repository\Article;

use App\Entity\Article;
use Nette\Caching\Cache;

final class CachedArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private ArticleRepositoryInterface $inner,
        private Cache $cache,
    ) {
    }

    public function findById(int $id): ?Article
    {
        return $this->cache->load("article:$id", fn () => $this->inner->findById($id));
    }

    public function findAll(): array
    {
        return $this->cache->load('article:list', fn () => $this->inner->findAll());
    }

    public function save(Article $article): void
    {
        $this->inner->save($article);
        $this->invalidate($article);
    }

    public function delete(Article $article): void
    {
        $this->inner->delete($article);
        $this->invalidate($article);
    }

    private function invalidate(Article $article): void
    {
        $this->cache->remove("article:{$article->getId()}");
        $this->cache->remove('article:list');
    }
}
