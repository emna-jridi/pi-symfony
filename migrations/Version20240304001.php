<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240304001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute la colonne face_image à la table user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` ADD face_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` DROP COLUMN face_image');
    }
} 