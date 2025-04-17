<?php

declare(strict_types=1);

use App\Enum\Role;
use App\Entity\User;
use App\Enum\Action;
use App\Service\Authorization\AuthorAuthorizationStrategy;
use PHPUnit\Framework\TestCase;

final class AuthorAuthorizationStrategyTest extends TestCase
{
    private AuthorAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AuthorAuthorizationStrategy();
    }

    private function createUserWithId(string $email, string $password, Role $role, int $id): User
    {
        $user = new User($email, $password, $role);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }


    public function testCanCreateArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertTrue($this->strategy->canAccess($author, Action::CREATE_ARTICLE));
    }

    public function testCanUpdateOwnArticle(): void
    {
        $author = $this->createUserWithId('author@example.com', 'secret', Role::AUTHOR, 1);

        $this->assertTrue($this->strategy->canAccess($author, Action::UPDATE_OWN_ARTICLE));
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
