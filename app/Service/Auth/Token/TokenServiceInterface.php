<?php

declare(strict_types=1);

namespace App\Service\Auth\Token;

use App\Entity\User;

interface TokenServiceInterface
{
    public function encode(User $user): string;

    public function decode(string $jwt): ?array;
}
