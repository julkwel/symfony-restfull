<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418170127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE boo_user CHANGE _nom _nom VARCHAR(100) DEFAULT NULL, CHANGE _prenom _prenom VARCHAR(100) DEFAULT NULL, CHANGE _adresse _adresse VARCHAR(100) DEFAULT NULL, CHANGE _img_url _img_url VARCHAR(200) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE boo_user CHANGE _nom _nom VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE _prenom _prenom VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE _adresse _adresse VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE _img_url _img_url VARCHAR(200) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
