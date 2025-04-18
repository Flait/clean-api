<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Authorization;

use App\Entity\User;
use App\Enum\Role;
use App\Service\Authorization\AuthorizationStrategyInterface;
use App\Service\Authorization\AuthorizationStrategyResolver;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AuthorizationStrategyResolverTest extends TestCase
{
    public function testResolvesCorrectStrategy(): void
    {
        $mockStrategy = $this->createMock(AuthorizationStrategyInterface::class);
        $user = new User('test@example.com', 'hashed_pass', Role::AUTHOR);

        $resolver = new AuthorizationStrategyResolver([
            Role::AUTHOR->value => $mockStrategy,
        ]);

        $this->assertSame($mockStrategy, $resolver->resolve($user));
    }

    public function testThrowsIfStrategyMissing(): void
    {
        $user = new User('test@example.com', 'hashed_pass', Role::READER);

        $resolver = new AuthorizationStrategyResolver([]); // empty strategies

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No strategy found for role: reader');

        $resolver->resolve($user);
    }
}
