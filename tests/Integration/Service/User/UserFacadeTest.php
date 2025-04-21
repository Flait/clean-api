<?php

declare(strict_types=1);

namespace App\Tests\Service\User;

use App\DTO\User\UserListResponse;
use App\DTO\User\UserResponse;
use App\Enum\Role;
use App\Service\User\UserFacade;
use App\Tests\Trait\CreatesUserWithId;
use Nette\DI\Container;
use PHPUnit\Framework\TestCase;

final class UserFacadeTest extends TestCase
{
    use CreatesUserWithId;
    private Container $container;
    private UserFacade $facade;

    protected function setUp(): void
    {
        $this->container = require __DIR__ . '/../../../bootstrap.php';
        $this->facade = $this->container->getByType(UserFacade::class);
    }

    public function testListReturnsUserListResponse(): void
    {
        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $result = $this->facade->list($actor);

        $this->assertInstanceOf(UserListResponse::class, $result);
        $this->assertIsArray($result->users);
        $this->assertInstanceOf(UserResponse::class, $result->users[0]);
    }

    public function testReaderCannotListUsers(): void
    {
        $this->expectException(\App\Exception\ForbiddenException::class);

        $actor = $this->createUserWithId('reader@example.com', 'hash', 'Reader', Role::READER, 999);

        $this->facade->list($actor);
    }

    public function testAuthorCannotDeleteUser(): void
    {
        $this->expectException(\App\Exception\ForbiddenException::class);

        $actor = $this->createUserWithId('author@example.com', 'hash', 'Author', Role::AUTHOR, 5);

        $this->facade->delete($actor, 1); // trying to delete the admin
    }

    public function testAdminCanSeeUserDetail(): void
    {
        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $user = $this->facade->detail($actor, 1);

        $this->assertEquals(1, $user->id);
        $this->assertEquals('admin@example.com', $user->email);
        $this->assertInstanceOf(UserResponse::class, $user);
    }

    public function testDetailThrowsIfUserNotFound(): void
    {
        $this->expectException(\App\Exception\NotFoundException::class);

        $actor = $this->createUserWithId('admin@example.com', 'hash', 'Admin', Role::ADMIN, 1);

        $this->facade->detail($actor, 9999); // non-existent
    }
}
