<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Role;
use Doctrine\ORM\Mapping as ORM;
use Nette\Database\Table\ActiveRow;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(name: 'password_hash', type: 'string')]
    private string $passwordHash;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $role;

    public function __construct(string $email, string $passwordHash, string $name, Role $role)
    {
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->name = $name;
        $this->role = $role->value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): Role
    {
        return Role::from($this->role);
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role->value;
    }

    public static function fromRow(ActiveRow $row): self
    {
        $user = new self(
            email: $row->email,
            passwordHash: $row->password_hash,
            name: $row->name,
            role: Role::from($row->role),
        );

        $user->id = (int) $row->id;

        return $user;
    }
}
