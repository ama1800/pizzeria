<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204035713 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_categorie (categorie_source INT NOT NULL, categorie_target INT NOT NULL, INDEX IDX_E422D933D9ACD54A (categorie_source), INDEX IDX_E422D933C04985C5 (categorie_target), PRIMARY KEY(categorie_source, categorie_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_categorie ADD CONSTRAINT FK_E422D933D9ACD54A FOREIGN KEY (categorie_source) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_categorie ADD CONSTRAINT FK_E422D933C04985C5 FOREIGN KEY (categorie_target) REFERENCES categorie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie_categorie');
    }
}
