<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Authorization;

use App\Enum\Action;
use App\Enum\Role;
use App\Service\Authorization\AuthorAuthorizationStrategy;
use App\Tests\CreatesUserWithId;
use PHPUnit\Framework\TestCase;

final class AuthorAuthorizationStrategyTest extends TestCase
{
    use CreatesUserWithId;
    private AuthorAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AuthorAuthorizationStrategy();
    }

    public function testCanCreateArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertTrue($this->strategy->canAccess($author, Action::CREATE_ARTICLE));
    }

    public function testCanUpdateOwnArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertTrue($this->strategy->canAccess($author, Action::UPDATE_OWN_ARTICLE, $author->getId()));
    }

    public function testCannotDeleteOthersArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);
        $otherAuthorId = $author->getId() + 1;

        $this->assertFalse($this->strategy->canAccess($author, Action::DELETE_ARTICLE, $otherAuthorId));
    }

    public function testCanDeleteOwnArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertTrue($this->strategy->canAccess($author, Action::DELETE_ARTICLE, $author->getId()));
    }

    public function testUnknownActionReturnsFalse(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertFalse($this->strategy->canAccess($author, Action::VIEW_ARTICLE));
    }
}
