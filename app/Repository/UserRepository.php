<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\User\UpdateUserData;
use App\Entity\User;
use App\Helper\EntityChangeHelper;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;

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
            'email'         => $user->getEmail(),
            'password_hash' => $hashedPassword,
            'name'          => $user->getName(),
            'role'          => $user->getRole()->value,
        ]);
    }

    public function update(User $user, UpdateUserData $dto): void
    {
        $changes = EntityChangeHelper::diff($dto, $user);

        if (!$changes) {
            return;
        }

        $this->db->table('user')
            ->where('id', $user->getId())
            ->update($changes);
    }

    public function delete(int $id): void
    {
        $this->db->table('user')->where('id', $id)->delete();
    }

    private function hydrateUser(ActiveRow $row): User
    {
        return User::fromRow($row);
    }
}
