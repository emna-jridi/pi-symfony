<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428183819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature CHANGE statut statut VARCHAR(255) DEFAULT NULL, CHANGE candidat_id candidat_id  INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CC03BD09 FOREIGN KEY (offreId) REFERENCES offreemploi (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_candidature_offre ON candidature
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E33BD3B8CC03BD09 ON candidature (offreId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat CHANGE StatusContrat StatusContrat VARCHAR(255) NOT NULL, CHANGE NomClient NomClient VARCHAR(255) NOT NULL, CHANGE EmailClient EmailClient VARCHAR(255) NOT NULL, CHANGE telephoneClient telephoneClient VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_services ADD PRIMARY KEY (contrat_id, service_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_services ADD CONSTRAINT FK_CAFDFB331823061F FOREIGN KEY (contrat_id) REFERENCES contrat (idContrat) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_services ADD CONSTRAINT FK_CAFDFB33ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (idService) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAFDFB331823061F ON contrat_services (contrat_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CAFDFB33ED5CA9E6 ON contrat_services (service_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contratemploye CHANGE StatusContrat StatusContrat VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contratemploye ADD CONSTRAINT FK_73CA11D66B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (ID_User) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_73CA11D66B3CA4B ON contratemploye (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE nom_formation nom_formation VARCHAR(255) NOT NULL, CHANGE theme_formation theme_formation VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE lien_formation lien_formation VARCHAR(255) NOT NULL, CHANGE niveau_difficulte niveau_difficulte VARCHAR(255) NOT NULL, CHANGE niveau niveau VARCHAR(255) DEFAULT NULL, CHANGE duree duree INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offreemploi CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_employe ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_bureau ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle CHANGE DureeReservation DureeReservation INT NOT NULL, CHANGE StatutReservation StatutReservation VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion CHANGE Id Id INT AUTO_INCREMENT NOT NULL, CHANGE description description VARCHAR(1000) NOT NULL, ADD PRIMARY KEY (Id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion ADD CONSTRAINT FK_5B00A4826B3CA4B FOREIGN KEY (id_user) REFERENCES `user` (ID_User)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5B00A4826B3CA4B ON reunion (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle CHANGE RefSalle RefSalle VARCHAR(255) NOT NULL, CHANGE Disponibilite Disponibilite VARCHAR(255) NOT NULL, CHANGE TypeSalle TypeSalle VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE NomService NomService VARCHAR(255) NOT NULL, CHANGE DescriptionService DescriptionService LONGTEXT NOT NULL, CHANGE TypeService TypeService VARCHAR(255) NOT NULL, CHANGE StatusService StatusService VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_teletravail_employe ON teletravail
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teletravail CHANGE StatutTT StatutTT VARCHAR(255) NOT NULL, CHANGE RaisonTT RaisonTT VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique_question_technique DROP FOREIGN KEY fk_ttqt_question
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_ttqt_question ON test_technique_question_technique
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_39AA27688C603064 ON test_technique_question_technique (question_technique_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique_question_technique ADD CONSTRAINT fk_ttqt_question FOREIGN KEY (question_technique_id) REFERENCES question_technique (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE role role VARCHAR(20) NOT NULL, CHANGE isActive isActive TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6496BFFC268 ON user (EmailUser)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CC03BD09
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CC03BD09
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature CHANGE statut statut VARCHAR(255) DEFAULT 'En_cours', CHANGE candidat_id  candidat_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_e33bd3b8cc03bd09 ON candidature
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_candidature_offre ON candidature (offreId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CC03BD09 FOREIGN KEY (offreId) REFERENCES offreemploi (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat CHANGE StatusContrat StatusContrat VARCHAR(100) NOT NULL, CHANGE NomClient NomClient VARCHAR(100) NOT NULL, CHANGE EmailClient EmailClient VARCHAR(100) NOT NULL, CHANGE telephoneClient telephoneClient VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contratemploye DROP FOREIGN KEY FK_73CA11D66B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_73CA11D66B3CA4B ON contratemploye
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contratemploye CHANGE StatusContrat StatusContrat VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_services DROP FOREIGN KEY FK_CAFDFB331823061F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat_services DROP FOREIGN KEY FK_CAFDFB33ED5CA9E6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CAFDFB331823061F ON contrat_services
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_CAFDFB33ED5CA9E6 ON contrat_services
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON contrat_services
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE nom_formation nom_formation VARCHAR(100) NOT NULL, CHANGE theme_formation theme_formation VARCHAR(100) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE lien_formation lien_formation VARCHAR(255) DEFAULT NULL, CHANGE niveau_difficulte niveau_difficulte VARCHAR(50) DEFAULT NULL, CHANGE niveau niveau VARCHAR(50) DEFAULT NULL, CHANGE duree duree INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offreemploi CHANGE statut statut VARCHAR(255) DEFAULT 'En cours'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle CHANGE DureeReservation DureeReservation TIME NOT NULL, CHANGE StatutReservation StatutReservation VARCHAR(50) DEFAULT 'En Attente' NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_employe ON reservation_salle (IdEmploye)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_bureau ON reservation_salle (IdSalle)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion MODIFY Id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion DROP FOREIGN KEY FK_5B00A4826B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5B00A4826B3CA4B ON reunion
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON reunion
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reunion CHANGE Id Id INT NOT NULL, CHANGE description description VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle CHANGE RefSalle RefSalle VARCHAR(50) NOT NULL, CHANGE Disponibilite Disponibilite VARCHAR(50) NOT NULL, CHANGE TypeSalle TypeSalle VARCHAR(50) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE NomService NomService VARCHAR(50) NOT NULL, CHANGE DescriptionService DescriptionService TEXT NOT NULL, CHANGE TypeService TypeService VARCHAR(100) NOT NULL, CHANGE StatusService StatusService VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teletravail CHANGE StatutTT StatutTT VARCHAR(100) DEFAULT 'En attente' NOT NULL, CHANGE RaisonTT RaisonTT VARCHAR(150) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_teletravail_employe ON teletravail (IdEmploye)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique CHANGE created_at created_at DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique_question_technique DROP FOREIGN KEY FK_39AA27688C603064
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_39aa27688c603064 ON test_technique_question_technique
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_ttqt_question ON test_technique_question_technique (question_technique_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_technique_question_technique ADD CONSTRAINT FK_39AA27688C603064 FOREIGN KEY (question_technique_id) REFERENCES question_technique (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6496BFFC268 ON `user`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` CHANGE role role VARCHAR(255) NOT NULL, CHANGE isActive isActive TINYINT(1) DEFAULT 1 NOT NULL
        SQL);
    }
}
