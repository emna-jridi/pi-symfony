<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409214107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat MODIFY id_contrat INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON contrat');
        $this->addSql('ALTER TABLE contrat CHANGE id_contrat idContrat INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE contrat ADD PRIMARY KEY (idContrat)');
        $this->addSql('ALTER TABLE contrat_services ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE contrat_services ADD CONSTRAINT FK_CAFDFB331823061F FOREIGN KEY (contrat_id) REFERENCES contrat (idContrat) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrat_services ADD CONSTRAINT FK_CAFDFB33ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (idService) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resultsp ADD CONSTRAINT FK_9842E928BF396750 FOREIGN KEY (id) REFERENCES testp (id)');
        $this->addSql('ALTER TABLE test_assignments ADD CONSTRAINT FK_6FF4EB638C03F15C FOREIGN KEY (employee_id) REFERENCES user (ID_User)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrat MODIFY idContrat INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON contrat');
        $this->addSql('ALTER TABLE contrat CHANGE idContrat id_contrat INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE contrat ADD PRIMARY KEY (id_contrat)');
        $this->addSql('ALTER TABLE contrat_services MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE contrat_services DROP FOREIGN KEY FK_CAFDFB331823061F');
        $this->addSql('ALTER TABLE contrat_services DROP FOREIGN KEY FK_CAFDFB33ED5CA9E6');
        $this->addSql('DROP INDEX `PRIMARY` ON contrat_services');
        $this->addSql('ALTER TABLE contrat_services DROP id');
        $this->addSql('ALTER TABLE contrat_services ADD PRIMARY KEY (contrat_id, service_id)');
        $this->addSql('ALTER TABLE resultsp DROP FOREIGN KEY FK_9842E928BF396750');
        $this->addSql('ALTER TABLE test_assignments DROP FOREIGN KEY FK_6FF4EB638C03F15C');
    }
}
