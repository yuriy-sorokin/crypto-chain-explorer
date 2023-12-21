<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221140656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crypto_asset (abbreviation VARCHAR(255) NOT NULL, PRIMARY KEY(abbreviation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crypto_asset_request (id INT UNSIGNED AUTO_INCREMENT NOT NULL, crypto_asset_id VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, threshold INT UNSIGNED NOT NULL, unique_context VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EDAE4D8B349698F6 (unique_context), INDEX IDX_EDAE4D8B35BE9A0E (crypto_asset_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE crypto_asset_response (id INT UNSIGNED AUTO_INCREMENT NOT NULL, request INT UNSIGNED NOT NULL, count INT UNSIGNED NOT NULL, average_quantity INT UNSIGNED NOT NULL, UNIQUE INDEX UNIQ_2477E6443B978F9F (request), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE crypto_asset_request ADD CONSTRAINT FK_EDAE4D8B35BE9A0E FOREIGN KEY (crypto_asset_id) REFERENCES crypto_asset (abbreviation)');
        $this->addSql('ALTER TABLE crypto_asset_response ADD CONSTRAINT FK_2477E6443B978F9F FOREIGN KEY (request) REFERENCES crypto_asset_request (id)');
        $this->addSql('INSERT INTO crypto_asset SET abbreviation = "btc"');
        $this->addSql('INSERT INTO crypto_asset SET abbreviation = "eth"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE crypto_asset_request DROP FOREIGN KEY FK_EDAE4D8B35BE9A0E');
        $this->addSql('ALTER TABLE crypto_asset_response DROP FOREIGN KEY FK_2477E6443B978F9F');
        $this->addSql('DROP TABLE crypto_asset');
        $this->addSql('DROP TABLE crypto_asset_request');
        $this->addSql('DROP TABLE crypto_asset_response');
    }
}
