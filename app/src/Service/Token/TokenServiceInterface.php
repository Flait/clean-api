<?php

namespace App\Service\Token;

use App\Entity\User;

interface TokenServiceInterface
{
    public function encode(User $user): string;

    public function decode(string $jwt): ?array;
}
