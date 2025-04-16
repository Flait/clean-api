<?php

declare(strict_types=1);

use App\Entity\User;
use App\Enum\Role;
use App\Exception\InvalidCredentialsException;
use App\Repository\UserRepositoryInterface;
use App\Service\AuthService;
use App\Service\TokenServiceInterface;
use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private UserRepositoryInterface $userRepoMock;
    private TokenServiceInterface $tokenServiceMock;

    protected function setUp(): void
    {
        // Mocking the UserRepositoryInterface and TokenServiceInterface
        $this->userRepoMock = $this->createMock(UserRepositoryInterface::class);
        $this->tokenServiceMock = $this->createMock(TokenServiceInterface::class);

        // AuthService depends on UserRepositoryInterface and TokenServiceInterface
        $this->authService = new AuthService(
            $this->userRepoMock,
            $this->tokenServiceMock
        );
    }

    public function testValidLoginReturnsJwt(): void
    {
        $email = 'test@example.com';
        $password = 'secret';
        $user = new User($email, password_hash($password, PASSWORD_DEFAULT), Role::Author);

        $this->userRepoMock->method('findByEmail')->with($email)->willReturn($user);

        $this->tokenServiceMock->method('encode')->with($user)->willReturn('jwt_token');

        $result = $this->authService->login($email, $password);

        $this->assertSame('jwt_token', $result);
    }

    public function testInvalidPasswordThrows(): void
    {
        $email = 'test@example.com';
        $user = new User($email, password_hash('correct_password', PASSWORD_DEFAULT), Role::Author);

        $this->userRepoMock->method('findByEmail')->with($email)->willReturn($user);

        $this->expectException(InvalidCredentialsException::class);

        $this->authService->login($email, 'wrong_password');
    }

    public function testUnknownUserThrows(): void
    {
        $this->userRepoMock->method('findByEmail')->willReturn(null);

        $this->expectException(InvalidCredentialsException::class);

        $this->authService->login('nonexistent@example.com', 'any_password');
    }
}
