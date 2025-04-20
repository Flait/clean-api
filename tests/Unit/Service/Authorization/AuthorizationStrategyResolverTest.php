<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Authorization;

use App\Enum\Role;
use App\Service\Authorization\AuthorizationStrategyInterface;
use App\Service\Authorization\AuthorizationStrategyResolver;
use App\Tests\CreatesUserWithId;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AuthorizationStrategyResolverTest extends TestCase
{
    use CreatesUserWithId;

    public function testResolvesCorrectStrategy(): void
    {
        $mockStrategy = $this->createMock(AuthorizationStrategyInterface::class);
        $user = $this->createUserWithId('test@example.com', 'hashed_pass', 'test', Role::AUTHOR, 1);

        $resolver = new AuthorizationStrategyResolver([
            Role::AUTHOR->value => $mockStrategy,
        ]);

        $this->assertSame($mockStrategy, $resolver->resolve($user));
    }

    public function testThrowsIfStrategyMissing(): void
    {
        $user = $this->createUserWithId('test@example.com', 'hashed_pass', 'test', Role::READER, 1);

        $resolver = new AuthorizationStrategyResolver([]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No strategy found for role: reader');

        $resolver->resolve($user);
    }
}
