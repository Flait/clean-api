<?php

declare(strict_types=1);

namespace App\Tests\Service\Authorization;

use App\Entity\User;
use App\Enum\Action;
use App\Enum\Role;
use App\Exception\ForbiddenException;
use App\Service\Authorization\AdminAuthorizationStrategy;
use App\Service\Authorization\AuthorAuthorizationStrategy;
use App\Service\Authorization\AuthorizationService;
use App\Service\Authorization\AuthorizationStrategyResolver;
use App\Service\Authorization\ReaderAuthorizationStrategy;
use App\Tests\CreatesUserWithId;
use PHPUnit\Framework\TestCase;

final class AuthorizationServiceTest extends TestCase
{
    use CreatesUserWithId;
    private AuthorizationService $service;

    protected function setUp(): void
    {
        $resolver = new AuthorizationStrategyResolver([
            Role::ADMIN->value  => new AdminAuthorizationStrategy(),
            Role::AUTHOR->value => new AuthorAuthorizationStrategy(),
            Role::READER->value => new ReaderAuthorizationStrategy(),
        ]);

        $this->service = new AuthorizationService($resolver);
    }

    public function testAdminCanDeleteAnyUser(): void
    {
        $admin = $this->createUserWithId('admin@example.com', 'password', Role::ADMIN, 1);
        $this->service->assertCan($admin, Action::DELETE_ARTICLE, 999);
        $this->addToAssertionCount(1);
    }

    public function testReaderCannotCreateArticle(): void
    {
        $this->expectException(ForbiddenException::class);

        $reader = $this->createUserWithId('reader@example.com', 'password', Role::READER, 1);
        $this->service->assertCan($reader, Action::CREATE_ARTICLE);
    }

    public function testAuthorCanUpdateOwnArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'password', Role::AUTHOR, 3);
        $this->service->assertCan($author, Action::UPDATE_OWN_ARTICLE, 3);
        $this->addToAssertionCount(1);
    }

    public function testAuthorCannotUpdateOthersArticle(): void
    {
        $this->expectException(ForbiddenException::class);

        $author = $this->createUserWithId('author@example.com', 'password', Role::AUTHOR, 1);
        $this->service->assertCan($author, Action::UPDATE_OWN_ARTICLE, 9999999);
    }
}
