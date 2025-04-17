<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\Role;
use Nette\Database\Explorer;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Explorer $db,
    ) {
    }

    public function findByEmail(string $email): ?User
    {
        $row = $this->db->table('user')
            ->where('email', $email)
            ->fetch();

        if (!$row) {
            return null;
        }

        return new User(
            email: $row->email,
            password: $row->password,
            role: Role::from($row->role),
        );
    }

    public function save(User $user): void
    {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $this->db->table('user')->insert([
            'email' => $user->getEmail(),
            'password' => $hashedPassword,
            'role' => $user->getRole()->value,
        ]);
    }

    public function verifyPassword(User $user, string $password): bool
    {
        return password_verify($password, $user->getPassword());
    }
}
