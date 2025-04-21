<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Authorization;

use App\Enum\Action;
use App\Enum\Role;
use App\Service\Authorization\AdminAuthorizationStrategy;
use App\Tests\Trait\CreatesUserWithId;
use PHPUnit\Framework\TestCase;

final class AdminAuthorizationStrategyTest extends TestCase
{
    use CreatesUserWithId;
    private AdminAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new AdminAuthorizationStrategy();
    }

    public function testAdminCanDoAnything(): void
    {
        $admin = $this->createUserWithId('admin@example.com', 'secret', 'test', Role::ADMIN, 1);

        foreach (Action::cases() as $action) {
            $this->assertTrue($this->strategy->canAccess($admin, $action), "Admin should have access to {$action->value}");
        }
    }
}
