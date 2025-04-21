<?php

declare(strict_types=1);

namespace App\Repository\Article;

use App\DTO\Article\UpdateArticleData;
use App\Entity\Article;
use App\Entity\User;
use App\Helper\EntityChangeHelper;
use App\Repository\UserRepository;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

final class DatabaseArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private Explorer $db,
        private UserRepository $userRepository,
    ) {
    }

    public function findById(int $id): ?Article
    {
        $row = $this->db->table('article')->get($id);
        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function findAll(): array
    {
        $rows = $this->db->table('article')->fetchAll();
        $articles = [];

        foreach ($rows as $row) {
            $article = $this->hydrate($row);
            if ($article) {
                $articles[] = $article;
            }
        }

        return $articles;
    }

    public function insert(Article $article): void
    {
        $this->db->table('article')->insert([
            'title'      => $article->getTitle(),
            'content'    => $article->getContent(),
            'author_id'  => $article->getAuthor()->getId(),
            'created_at' => $article->getCreatedAt(),
            'updated_at' => $article->getUpdatedAt(),
        ]);
    }

    public function update(Article $article, UpdateArticleData $data): void
    {
        $changes = EntityChangeHelper::diff($data, $article);

        if (!$changes) {
            return;
        }

        $changes['updated_at'] = new \DateTimeImmutable();

        $this->db->table('article')
            ->where('id', $article->getId())
            ->update($changes);
    }

    public function delete(Article $article): void
    {
        $this->db->table('article')->where('id', $article->getId())->delete();
    }

    private function hydrate(ActiveRow $row): ?Article
    {
        $author = $this->userRepository->findById($row->author_id);
        if (!$author instanceof User) {
            return null;
        }
        return Article::fromRow($row, $author);
    }
}
