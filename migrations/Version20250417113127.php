<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417113127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql(<<<'SQL'
        //     CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, id_employe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        // SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_test (user_id INT NOT NULL, test_id INT NOT NULL, INDEX IDX_A2FE32C5A76ED395 (user_id), INDEX IDX_A2FE32C51E5D0459 (test_id), PRIMARY KEY(user_id, test_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test ADD CONSTRAINT FK_A2FE32C5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (ID_User)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test ADD CONSTRAINT FK_A2FE32C51E5D0459 FOREIGN KEY (test_id) REFERENCES test_technique (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultsp DROP FOREIGN KEY resultsp_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_assignments DROP FOREIGN KEY test_assignments_ibfk_1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE resultsp
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE testp
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE test_assignments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE test_results
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE test_resultsp
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_candidat ON candidature
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature CHANGE statut statut VARCHAR(255) DEFAULT NULL, CHANGE candidat_id candidat_id  INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CC03BD09 FOREIGN KEY (offreId) REFERENCES offreemploi (id)
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
            ALTER TABLE employés MODIFY IdEmploye INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON employés
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employés ADD nom_employe VARCHAR(255) NOT NULL, ADD prenom_employe VARCHAR(255) NOT NULL, ADD date_naissance_employe DATE NOT NULL, ADD adresse_employe VARCHAR(255) NOT NULL, ADD telephone_employe NUMERIC(10, 0) NOT NULL, ADD email_employe VARCHAR(255) NOT NULL, ADD poste_employe VARCHAR(255) NOT NULL, ADD date_embauche_employe DATE NOT NULL, ADD nb_ttvalidé INT NOT NULL, ADD nb_ttrefusé INT NOT NULL, DROP NomEmploye, DROP PrenomEmploye, DROP DateNaissanceEmploye, DROP AdresseEmploye, DROP TelephoneEmploye, DROP EmailEmploye, DROP PosteEmploye, DROP DateEmbaucheEmploye, DROP NbTTValidé, DROP NbTTRefusé, CHANGE IdEmploye id_employe INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employés ADD PRIMARY KEY (id_employe)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE nom_formation nom_formation VARCHAR(255) NOT NULL, CHANGE theme_formation theme_formation VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE lien_formation lien_formation VARCHAR(255) NOT NULL, CHANGE niveau_difficulte niveau_difficulte VARCHAR(255) NOT NULL, CHANGE niveau niveau VARCHAR(255) DEFAULT NULL, CHANGE duree duree INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offreemploi CHANGE statut statut VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions CHANGE question question LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle MODIFY IdReservation INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_employe ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_bureau ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle ADD id_employe INT NOT NULL, ADD id_salle INT NOT NULL, ADD statut_reservation VARCHAR(255) NOT NULL, DROP IdEmploye, DROP IdSalle, DROP StatutReservation, CHANGE IdReservation id_reservation INT AUTO_INCREMENT NOT NULL, CHANGE DateReservation date_reservation DATE NOT NULL, CHANGE DureeReservation duree_reservation TIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle ADD PRIMARY KEY (id_reservation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle MODIFY idSalle INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD ref_salle VARCHAR(255) NOT NULL, ADD type_salle VARCHAR(255) NOT NULL, DROP RefSalle, DROP TypeSalle, CHANGE Disponibilité disponibilité VARCHAR(255) NOT NULL, CHANGE idSalle id_salle INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD PRIMARY KEY (id_salle)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE NomService NomService VARCHAR(255) NOT NULL, CHANGE DescriptionService DescriptionService LONGTEXT NOT NULL, CHANGE TypeService TypeService VARCHAR(255) NOT NULL, CHANGE StatusService StatusService VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teletravail CHANGE statut_tt statut_tt VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat DROP FOREIGN KEY fk_test
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat DROP FOREIGN KEY fk_test
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat CHANGE date_passage date_passage DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat ADD CONSTRAINT FK_FFD6BF271E5D0459 FOREIGN KEY (test_id) REFERENCES test_technique (id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_test ON test_candidat
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FFD6BF271E5D0459 ON test_candidat (test_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat ADD CONSTRAINT fk_test FOREIGN KEY (test_id) REFERENCES test_technique (id) ON DELETE CASCADE
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
            CREATE TABLE resultsp (id INT AUTO_INCREMENT NOT NULL, test_id INT NOT NULL, employee_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, response_json TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE testp (id INT AUTO_INCREMENT NOT NULL, test_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, test_title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, test_link VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE test_assignments (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, nomEmployee VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, test_category VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, assigned_by INT NOT NULL, questions_count INT NOT NULL, deadline DATETIME NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT 'PENDING' NOT NULL COLLATE `utf8mb4_general_ci`, notes TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX employee_id (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE test_results (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, test_category VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, total_questions INT NOT NULL, correct_answers INT NOT NULL, score NUMERIC(5, 2) NOT NULL, analysis_notes TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, test_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE test_resultsp (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, test_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, test_type VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, raw_results TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, analysis_result LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE resultsp ADD CONSTRAINT resultsp_ibfk_1 FOREIGN KEY (id) REFERENCES testp (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_assignments ADD CONSTRAINT test_assignments_ibfk_1 FOREIGN KEY (employee_id) REFERENCES user (ID_User)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test DROP FOREIGN KEY FK_A2FE32C5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_test DROP FOREIGN KEY FK_A2FE32C51E5D0459
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE equipe
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_test
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CC03BD09
        // SQL);
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8CC03BD09
        // SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE candidature CHANGE statut statut VARCHAR(255) DEFAULT 'En_cours', CHANGE candidat_id  candidat_id INT DEFAULT NULL
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
            ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8CC03BD09 FOREIGN KEY (offreId) REFERENCES offreemploi (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contrat CHANGE StatusContrat StatusContrat VARCHAR(100) NOT NULL, CHANGE NomClient NomClient VARCHAR(100) NOT NULL, CHANGE EmailClient EmailClient VARCHAR(100) NOT NULL, CHANGE telephoneClient telephoneClient VARCHAR(20) NOT NULL
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
            ALTER TABLE employés MODIFY id_employe INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON employés
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employés ADD NomEmploye VARCHAR(50) NOT NULL, ADD PrenomEmploye VARCHAR(50) NOT NULL, ADD DateNaissanceEmploye DATE NOT NULL, ADD AdresseEmploye VARCHAR(100) NOT NULL, ADD TelephoneEmploye DOUBLE PRECISION NOT NULL, ADD EmailEmploye VARCHAR(100) NOT NULL, ADD PosteEmploye VARCHAR(100) NOT NULL, ADD DateEmbaucheEmploye DATE NOT NULL, ADD NbTTValidé INT NOT NULL, ADD NbTTRefusé INT NOT NULL, DROP nom_employe, DROP prenom_employe, DROP date_naissance_employe, DROP adresse_employe, DROP telephone_employe, DROP email_employe, DROP poste_employe, DROP date_embauche_employe, DROP nb_ttvalidé, DROP nb_ttrefusé, CHANGE id_employe IdEmploye INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employés ADD PRIMARY KEY (IdEmploye)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE formation CHANGE nom_formation nom_formation VARCHAR(100) NOT NULL, CHANGE theme_formation theme_formation VARCHAR(100) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE lien_formation lien_formation VARCHAR(255) DEFAULT NULL, CHANGE niveau_difficulte niveau_difficulte VARCHAR(50) DEFAULT NULL, CHANGE niveau niveau VARCHAR(50) DEFAULT NULL, CHANGE duree duree INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offreemploi CHANGE statut statut VARCHAR(255) DEFAULT 'En cours'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE questions CHANGE question question TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle MODIFY id_reservation INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON reservation_salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle ADD IdEmploye INT NOT NULL, ADD IdSalle INT NOT NULL, ADD StatutReservation VARCHAR(50) DEFAULT 'En Attente' NOT NULL, DROP id_employe, DROP id_salle, DROP statut_reservation, CHANGE id_reservation IdReservation INT AUTO_INCREMENT NOT NULL, CHANGE date_reservation DateReservation DATE NOT NULL, CHANGE duree_reservation DureeReservation TIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_employe ON reservation_salle (IdEmploye)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_bureau ON reservation_salle (IdSalle)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation_salle ADD PRIMARY KEY (IdReservation)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle MODIFY id_salle INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON salle
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD RefSalle VARCHAR(50) NOT NULL, ADD TypeSalle VARCHAR(50) NOT NULL, DROP ref_salle, DROP type_salle, CHANGE disponibilité Disponibilité VARCHAR(50) NOT NULL, CHANGE id_salle idSalle INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE salle ADD PRIMARY KEY (idSalle)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE services CHANGE NomService NomService VARCHAR(50) NOT NULL, CHANGE DescriptionService DescriptionService TEXT NOT NULL, CHANGE TypeService TypeService VARCHAR(100) NOT NULL, CHANGE StatusService StatusService VARCHAR(100) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE teletravail CHANGE statut_tt statut_tt VARCHAR(255) DEFAULT 'Traitement' NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat DROP FOREIGN KEY FK_FFD6BF271E5D0459
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat DROP FOREIGN KEY FK_FFD6BF271E5D0459
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat CHANGE date_passage date_passage DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat ADD CONSTRAINT fk_test FOREIGN KEY (test_id) REFERENCES test_technique (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_ffd6bf271e5d0459 ON test_candidat
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_test ON test_candidat (test_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE test_candidat ADD CONSTRAINT FK_FFD6BF271E5D0459 FOREIGN KEY (test_id) REFERENCES test_technique (id)
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
