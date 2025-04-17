<?php

declare(strict_types=1);

use App\Enum\Action;
use App\Enum\Role;
use App\Entity\User;
use App\Service\Authorization\ReaderAuthorizationStrategy;
use PHPUnit\Framework\TestCase;

final class ReaderAuthorizationStrategyTest extends TestCase
{
    private ReaderAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ReaderAuthorizationStrategy();
    }

    private function createUserWithId(int $id): User
    {
        $user = new User('reader@example.com', 'secret', Role::READER);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }

    public function testReaderCanOnlyViewArticle(): void
    {
        $reader = $this->createUserWithId(1);

        $this->assertTrue($this->strategy->canAccess($reader, Action::VIEW_ARTICLE));

        $disallowed = [
            Action::CREATE_ARTICLE,
            Action::UPDATE_OWN_ARTICLE,
            Action::DELETE_ARTICLE,
        ];

        foreach ($disallowed as $action) {
            $this->assertFalse($this->strategy->canAccess($reader, $action), "Reader should NOT access {$action->value}");
        }
    }
}
