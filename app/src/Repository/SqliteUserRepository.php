<?php

namespace App\Repository;

use App\Entity\User;
use App\Enum\Role;
use Nette\Database\Explorer;

final class SqliteUserRepository implements UserRepositoryInterface
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
        $this->db->table('user')->insert([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()->value,
        ]);
    }
}
