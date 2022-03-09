<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303215014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calendar');
        $this->addSql('ALTER TABLE calendar ADD title VARCHAR(255) DEFAULT NULL, ADD background_color VARCHAR(255) DEFAULT NULL, ADD border_color VARCHAR(255) DEFAULT NULL, DROP titre, DROP back_groundcolor, DROP bordor_color, CHANGE description description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE titre nom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD text VARCHAR(255) DEFAULT NULL, ADD jeu VARCHAR(255) DEFAULT NULL, DROP texte, DROP jeux, CHANGE categorie_id categorie_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, start DATETIME DEFAULT NULL, end DATETIME DEFAULT NULL, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, all_day TINYINT(1) DEFAULT NULL, background_color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, border_color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, text_color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE calendar ADD titre VARCHAR(255) NOT NULL, ADD back_groundcolor VARCHAR(255) DEFAULT NULL, ADD bordor_color VARCHAR(255) DEFAULT NULL, DROP title, DROP background_color, DROP border_color, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE nom titre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE news ADD texte VARCHAR(255) DEFAULT NULL, ADD jeux VARCHAR(255) DEFAULT NULL, DROP text, DROP jeu, CHANGE categorie_id categorie_id INT DEFAULT NULL');
    }
}
