<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309164201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL, all_day TINYINT(1) DEFAULT NULL, background_color VARCHAR(255) DEFAULT NULL, border_color VARCHAR(255) DEFAULT NULL, text_color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, news_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_E01FBE6AB5A459A0 (news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, jeu VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, count INT DEFAULT NULL, like_count INT DEFAULT NULL, dislike_count INT DEFAULT NULL, INDEX IDX_1DD39950BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD39950BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD39950BCF5E72D');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AB5A459A0');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE news');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
