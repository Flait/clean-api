<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421193803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert default admin account';
    }

    public function up(Schema $schema): void
    {
        $password = password_hash('admin123', PASSWORD_BCRYPT);
        $this->addSql("
        INSERT INTO user (email, name, password_hash, role)
        VALUES (
            'admin@example.com',
            'Test Admin',
            '$password',
            'admin'
        );
    ");

        $now = (new \DateTimeImmutable())->getTimestamp();

        $this->addSql("
        INSERT INTO article (title, content, author_id, created_at, updated_at)
        VALUES (
            'Welcome to Ehotel!',
            'This is the very first article.',
            1,
            '$now',
            '$now'
        );
    ");
    }
    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM article WHERE title = 'Welcome to Ehotel!'");
        $this->addSql("DELETE FROM user WHERE email = 'admin@example.com'");
    }

}
