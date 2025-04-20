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

    public function findById(int $id): ?User
    {
        $row = $this->db->table('user')
            ->where('id', $id)
            ->fetch();

        return $row ? $this->hydrateUser($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $row = $this->db->table('user')
            ->where('email', $email)
            ->fetch();

        return $row ? $this->hydrateUser($row) : null;
    }

    /** @return User[] */
    public function findAll(): array
    {
        $rows = $this->db->table('user')->fetchAll();
        $users = [];

        foreach ($rows as $row) {
            $users[] = $this->hydrateUser($row);
        }

        return $users;
    }

    public function save(User $user): void
    {
        $hashedPassword = password_hash($user->getPasswordHash(), PASSWORD_BCRYPT);
        $this->db->table('user')->insert([
            'email'        => $user->getEmail(),
            'passwordHash' => $hashedPassword,
            'name'         => $user->getName(),
            'role'         => $user->getRole()->value,
        ]);
    }

    public function delete(int $id): void
    {
        $this->db->table('user')->where('id', $id)->delete();
    }

    private function hydrateUser(\Nette\Database\Table\ActiveRow $row): User
    {
        return new User(
            email: $row->email,
            passwordHash: $row->passwordHash ?? $row->password, // fallback in case of legacy column
            name: $row->name,
            role: Role::from($row->role),
        );
    }
}
