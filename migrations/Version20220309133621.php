<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309133621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, rank INT DEFAULT NULL, categorie VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, coach_id INT DEFAULT NULL, tempsstart DATETIME NOT NULL, tempsend DATETIME NOT NULL, dispo TINYINT(1) DEFAULT NULL, INDEX IDX_42C849553C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553C105691');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
