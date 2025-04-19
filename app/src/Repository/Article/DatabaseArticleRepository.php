<?php

namespace App\Repository\Article;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

final class DatabaseArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function findById(int $id): ?Article
    {
        return $this->em->getRepository(Article::class)->find($id);
    }

    public function findAll(): array
    {
        return $this->em->getRepository(Article::class)->findAll();
    }

    public function save(Article $article): void
    {
        $this->em->persist($article);
        $this->em->flush();
    }

    public function delete(Article $article): void
    {
        $this->em->remove($article);
        $this->em->flush();
    }
}
