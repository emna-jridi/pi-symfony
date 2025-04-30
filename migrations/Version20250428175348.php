<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428175348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE employés (id_employe INT AUTO_INCREMENT NOT NULL, nom_employe VARCHAR(255) NOT NULL, prenom_employe VARCHAR(255) NOT NULL, date_naissance_employe DATE NOT NULL, adresse_employe VARCHAR(255) NOT NULL, telephone_employe NUMERIC(10, 0) NOT NULL, email_employe VARCHAR(255) NOT NULL, poste_employe VARCHAR(255) NOT NULL, date_embauche_employe DATE NOT NULL, nb_ttvalidé INT NOT NULL, nb_ttrefusé INT NOT NULL, PRIMARY KEY(id_employe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, id_employe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE test_candidat (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, nom_candidat VARCHAR(255) NOT NULL, email_candidat VARCHAR(255) NOT NULL, reponses JSON NOT NULL COMMENT '(DC2Type:json)', score INT NOT NULL, date_passage DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', termine TINYINT(1) NOT NULL, INDEX IDX_FFD6BF271E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE test_results (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, test_id INT NOT NULL, test_category VARCHAR(255) NOT NULL, total_questions INT NOT NULL, correct_answers INT NOT NULL, score NUMERIC(10, 0) NOT NULL, analysis_notes LONGTEXT DEFAULT NULL, test_date DATETIME NOT NULL, INDEX IDX_43E230DCA76ED395 (user_id), INDEX IDX_43E230DC1E5D0459 (test_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat ADD CONSTRAINT FK_FFD6BF271E5D0459 FOREIGN KEY (test_id) REFERENCES test_technique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_results ADD CONSTRAINT FK_43E230DCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (ID_User)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_results ADD CONSTRAINT FK_43E230DC1E5D0459 FOREIGN KEY (test_id) REFERENCES test_technique (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE conge
        SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE candidature DROP FOREIGN KEY fk_candidature_offre
        // SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_candidat ON candidature
        SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE candidature DROP FOREIGN KEY fk_candidature_offre
        // SQL);
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
            ALTER TABLE candidature ADD CONSTRAINT fk_candidature_offre FOREIGN KEY (offreId) REFERENCES offreemploi (id) ON UPDATE CASCADE ON DELETE CASCADE
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
            CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, Type_conge VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, Date_debut DATE NOT NULL, Status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, Date_fin DATE NOT NULL, INDEX IDX_2ED893486B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat DROP FOREIGN KEY FK_FFD6BF271E5D0459
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_results DROP FOREIGN KEY FK_43E230DCA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_results DROP FOREIGN KEY FK_43E230DC1E5D0459
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employés
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE test_candidat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE test_results
        SQL);
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
            ALTER TABLE candidature ADD CONSTRAINT fk_candidature_offre FOREIGN KEY (offreId) REFERENCES offreemploi (id) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_candidat ON candidature (candidat_id)
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
