<?php

declare(strict_types=1);

use App\Enum\Action;
use App\Enum\Role;
use App\Entity\User;
use App\Service\Authorization\AdminAuthorizationStrategy;
use PHPUnit\Framework\TestCase;

final class AdminAuthorizationStrategyTest extends TestCase
{
    private AdminAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AdminAuthorizationStrategy();
    }

    private function createUserWithId(int $id): User
    {
        $user = new User('admin@example.com', 'secret', Role::ADMIN);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }

    public function testAdminCanDoAnything(): void
    {
        $admin = $this->createUserWithId(1);

        foreach (Action::cases() as $action) {
            $this->assertTrue($this->strategy->canAccess($admin, $action), "Admin should have access to {$action->value}");
        }
    }
}
