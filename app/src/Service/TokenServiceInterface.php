<?php

namespace App\Service;

use App\Entity\User;

interface TokenServiceInterface
{
    public function encode(User $user): string;

    public function decode(string $jwt): ?array;
}
