<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210208215437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A7982EA2E54');
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A79CCD7E912');
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A79F347EFB');
        $this->addSql('DROP INDEX IDX_8BF24A7982EA2E54 ON quantite');
        $this->addSql('DROP INDEX IDX_8BF24A79F347EFB ON quantite');
        $this->addSql('DROP INDEX IDX_8BF24A79CCD7E912 ON quantite');
        $this->addSql('ALTER TABLE quantite DROP produit_id, DROP commande_id, DROP menu_id, DROP quantite');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantite ADD produit_id INT NOT NULL, ADD commande_id INT NOT NULL, ADD menu_id INT DEFAULT NULL, ADD quantite INT NOT NULL');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A7982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A79CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A79F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_8BF24A7982EA2E54 ON quantite (commande_id)');
        $this->addSql('CREATE INDEX IDX_8BF24A79F347EFB ON quantite (produit_id)');
        $this->addSql('CREATE INDEX IDX_8BF24A79CCD7E912 ON quantite (menu_id)');
    }
}
