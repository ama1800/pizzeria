<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130003921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu ADD disponible TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE produits_du_menus RENAME INDEX idx_66ea34b6ccd7e912 TO IDX_396A3386CCD7E912');
        $this->addSql('ALTER TABLE produits_du_menus RENAME INDEX idx_66ea34b6f347efb TO IDX_396A3386F347EFB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP disponible');
        $this->addSql('ALTER TABLE produits_du_menus RENAME INDEX idx_396a3386f347efb TO IDX_66EA34B6F347EFB');
        $this->addSql('ALTER TABLE produits_du_menus RENAME INDEX idx_396a3386ccd7e912 TO IDX_66EA34B6CCD7E912');
    }
}
