<?php

declare(strict_types=1);

namespace App\Tests\Trait;

use App\Entity\User;
use App\Enum\Role;

trait CreatesUserWithId
{
    protected function createUserWithId(string $email, string $password, $name, Role $role, int $id): User
    {
        $user = new User($email, $password, $name, $role);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }
}
