<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406122013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE conge (id_user INT DEFAULT NULL, Id INT AUTO_INCREMENT NOT NULL, Type_conge VARCHAR(255) NOT NULL, Date_debut DATE NOT NULL, Status VARCHAR(255) NOT NULL, Date_fin DATE NOT NULL, INDEX IDX_2ED893486B3CA4B (id_user), PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (ID_User INT AUTO_INCREMENT NOT NULL, NomUser VARCHAR(255) NOT NULL, PrenomUser VARCHAR(255) NOT NULL, DateNaissanceUser DATE NOT NULL, AdresseUser VARCHAR(255) NOT NULL, TelephoneUser VARCHAR(20) NOT NULL, EmailUser VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, Password VARCHAR(255) NOT NULL, isActive TINYINT(1) NOT NULL, reset_code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(ID_User)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE conge ADD CONSTRAINT FK_2ED893486B3CA4B FOREIGN KEY (id_user) REFERENCES user (ID_User)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE conge DROP FOREIGN KEY FK_2ED893486B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE conge
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
