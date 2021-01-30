<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126214843 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantite ADD menu_id INT DEFAULT NULL, CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A79CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE INDEX IDX_8BF24A79CCD7E912 ON quantite (menu_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A79CCD7E912');
        $this->addSql('DROP INDEX IDX_8BF24A79CCD7E912 ON quantite');
        $this->addSql('ALTER TABLE quantite DROP menu_id, CHANGE produit_id produit_id INT NOT NULL');
    }
}
