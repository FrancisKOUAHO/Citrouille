<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402180119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(25) NOT NULL, prenom VARCHAR(25) NOT NULL, date_de_naissance BIGINT NOT NULL, login VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(100) NOT NULL, classe VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, createur_id INT NOT NULL, nom VARCHAR(100) NOT NULL, visibilite INT NOT NULL, date_creation BIGINT NOT NULL, INDEX IDX_FCF22AF473A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professeur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, login VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, reponse VARCHAR(100) NOT NULL, url_image VARCHAR(255) DEFAULT NULL, url_audio VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_liste (question_id INT NOT NULL, liste_id INT NOT NULL, INDEX IDX_CC193BF01E27F6BF (question_id), INDEX IDX_CC193BF0E85441D8 (liste_id), PRIMARY KEY(question_id, liste_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF473A201E5 FOREIGN KEY (createur_id) REFERENCES professeur (id)');
        $this->addSql('ALTER TABLE question_liste ADD CONSTRAINT FK_CC193BF01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_liste ADD CONSTRAINT FK_CC193BF0E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_liste DROP FOREIGN KEY FK_CC193BF0E85441D8');
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF473A201E5');
        $this->addSql('ALTER TABLE question_liste DROP FOREIGN KEY FK_CC193BF01E27F6BF');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE professeur');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_liste');
    }
}
