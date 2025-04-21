<?php

declare(strict_types=1);

namespace App\Tests\Service\Article;

use App\DTO\Article\ArticleListResponse;
use App\DTO\Article\ArticleResponse;
use App\Enum\Role;
use App\Service\Article\ArticleFacade;
use App\Tests\Trait\CreatesUserWithId;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;

final class ArticleFacadeTest extends TestCase
{
    use CreatesUserWithId;

    private Container $container;
    private ArticleFacade $facade;

    protected function setUp(): void
    {
        $this->container = require __DIR__ . '/../../../bootstrap.php';
        $this->facade = $this->container->getByType(ArticleFacade::class);
    }

    public function testListReturnsArticleListResponse(): void
    {
        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $result = $this->facade->list($actor);

        $this->assertInstanceOf(ArticleListResponse::class, $result);
        $this->assertIsArray($result->articles);
        $this->assertInstanceOf(ArticleResponse::class, $result->articles[0]);
    }

    public function testDetailReturnsArticleResponse(): void
    {
        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $result = $this->facade->detail($actor, 1);

        $this->assertInstanceOf(ArticleResponse::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertSame('Welcome to Ehotel!', $result->title);
    }

    public function testDetailThrowsWhenArticleNotFound(): void
    {
        $this->expectException(\App\Exception\NotFoundException::class);

        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $this->facade->detail($actor, 999);
    }

    public function testAdminCanCreateArticle(): void
    {
        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $this->facade->create($actor, new \App\DTO\Article\CreateArticleData(
            title: '123 Test Article',
            content: 'Integration test content'
        ));

        $articles = $this->facade->list($actor)->articles;

        $titles = array_map(fn ($a) => $a->title, $articles);

        $this->assertContains('123 Test Article', $titles);
    }

    protected function tearDown(): void
    {
        $this->container->getByType(\Nette\Database\Explorer::class)
            ->table('article')
            ->where('title', '123 Test Article')
            ->delete();
    }
}
