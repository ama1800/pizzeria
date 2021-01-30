<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130014606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produits_du_menus');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_du_menus (id INT AUTO_INCREMENT NOT NULL, menu_id INT NOT NULL, produit_id INT DEFAULT NULL, INDEX IDX_396A3386F347EFB (produit_id), INDEX IDX_396A3386CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE produits_du_menus ADD CONSTRAINT FK_66EA34B6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE produits_du_menus ADD CONSTRAINT FK_66EA34B6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }
}
