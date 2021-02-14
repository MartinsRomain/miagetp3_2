<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210214124614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL)');
        $this->addSql('DROP INDEX IDX_F65593E560BB6FE6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__annonce AS SELECT id, auteur_id, nom, description, prix FROM annonce');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('CREATE TABLE annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auteur_id INTEGER DEFAULT NULL, categorie_id INTEGER DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, prix INTEGER DEFAULT NULL, CONSTRAINT FK_F65593E560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F65593E5BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO annonce (id, auteur_id, nom, description, prix) SELECT id, auteur_id, nom, description, prix FROM __temp__annonce');
        $this->addSql('DROP TABLE __temp__annonce');
        $this->addSql('CREATE INDEX IDX_F65593E560BB6FE6 ON annonce (auteur_id)');
        $this->addSql('CREATE INDEX IDX_F65593E5BCF5E72D ON annonce (categorie_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX IDX_F65593E560BB6FE6');
        $this->addSql('DROP INDEX IDX_F65593E5BCF5E72D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__annonce AS SELECT id, auteur_id, nom, description, prix FROM annonce');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('CREATE TABLE annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auteur_id INTEGER DEFAULT NULL, nom VARCHAR(100) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, prix INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO annonce (id, auteur_id, nom, description, prix) SELECT id, auteur_id, nom, description, prix FROM __temp__annonce');
        $this->addSql('DROP TABLE __temp__annonce');
        $this->addSql('CREATE INDEX IDX_F65593E560BB6FE6 ON annonce (auteur_id)');
    }
}
