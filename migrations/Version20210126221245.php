<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126221245 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_du_menu DROP FOREIGN KEY FK_66EA34B6CD11A2CF');
        $this->addSql('DROP INDEX IDX_66EA34B6CD11A2CF ON produits_du_menu');
        $this->addSql('ALTER TABLE produits_du_menu CHANGE produits_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits_du_menu ADD CONSTRAINT FK_66EA34B6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_66EA34B6F347EFB ON produits_du_menu (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_du_menu DROP FOREIGN KEY FK_66EA34B6F347EFB');
        $this->addSql('DROP INDEX IDX_66EA34B6F347EFB ON produits_du_menu');
        $this->addSql('ALTER TABLE produits_du_menu CHANGE produit_id produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits_du_menu ADD CONSTRAINT FK_66EA34B6CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_66EA34B6CD11A2CF ON produits_du_menu (produits_id)');
    }
}
