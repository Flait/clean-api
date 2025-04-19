<?php

namespace App\Tests;

use App\Entity\User;
use App\Enum\Role;

trait CreatesUserWithId
{
    protected function createUserWithId(string $email, string $password, Role $role, int $id): User
    {
        $user = new User($email, $password, $role);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }
}
