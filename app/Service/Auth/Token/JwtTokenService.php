<?php

declare(strict_types=1);

namespace App\Service\Auth\Token;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class JwtTokenService implements TokenServiceInterface
{
    public function __construct(
        private string $secret,
        private string $algo = 'HS256',
    ) {
    }

    public function encode(User $user): string
    {
        $payload = [
            'sub'  => $user->getEmail(),
            'role' => $user->getRole()->value,
            'iat'  => time(),
            'exp'  => time() + 360000,
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function decode(string $jwt): ?array
    {
        try {
            return (array) JWT::decode($jwt, new Key($this->secret, $this->algo));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
