<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Pimcore\Migrations\BundleAwareMigration;

final class Version20240525180313 extends BundleAwareMigration
{
    public function getDescription() : string
    {
        return 'Create tables for Location, Player, PlayerPosition, Team, and Trainer entities';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS locations (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            lat DOUBLE PRECISION NOT NULL,
            lon DOUBLE PRECISION NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS player_positions (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS trainers (
            id INT AUTO_INCREMENT NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS teams (
            id INT AUTO_INCREMENT NOT NULL,
            trainer_id INT DEFAULT NULL,
            location_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            logo VARCHAR(255) DEFAULT NULL,
            founded_at INT NOT NULL,
            PRIMARY KEY(id),
            INDEX IDX_C4E0A61F41807E1D (trainer_id),
            INDEX IDX_C4E0A61F64D218E (location_id),
            CONSTRAINT FK_C4E0A61F41807E1D FOREIGN KEY (trainer_id) REFERENCES trainers (id) ON DELETE SET NULL,
            CONSTRAINT FK_C4E0A61F64D218E FOREIGN KEY (location_id) REFERENCES locations (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE IF NOT EXISTS players (
            id INT AUTO_INCREMENT NOT NULL,
            position_id INT NOT NULL,
            team_id INT DEFAULT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            field_number INT NOT NULL,
            age INT NOT NULL,
            PRIMARY KEY(id),
            INDEX IDX_BCF5E72BCADD40FE (position_id),
            INDEX IDX_BCF5E72B296CD8AE (team_id),
            CONSTRAINT FK_BCF5E72BCADD40FE FOREIGN KEY (position_id) REFERENCES player_positions (id),
            CONSTRAINT FK_BCF5E72B296CD8AE FOREIGN KEY (team_id) REFERENCES teams (id) ON DELETE SET NULL
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_BCF5E72BCADD40FE');
        $this->addSql('ALTER TABLE players DROP FOREIGN KEY FK_BCF5E72B296CD8AE');
        $this->addSql('ALTER TABLE teams DROP FOREIGN KEY FK_C4E0A61F41807E1D');
        $this->addSql('ALTER TABLE teams DROP FOREIGN KEY FK_C4E0A61F64D218E');
        $this->addSql('DROP TABLE IF EXISTS locations');
        $this->addSql('DROP TABLE IF EXISTS players');
        $this->addSql('DROP TABLE IF EXISTS player_positions');
        $this->addSql('DROP TABLE IF EXISTS teams');
        $this->addSql('DROP TABLE IF EXISTS trainers');
    }

    public function getBundleName(): string
    {
        return 'AppBundle';
    }
}
