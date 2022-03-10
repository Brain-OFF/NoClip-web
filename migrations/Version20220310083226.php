<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310083226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, promos_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, descreption VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_FF232B31CAA392D2 (promos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_gamescat (games_id INT NOT NULL, gamescat_id INT NOT NULL, INDEX IDX_C6E727D797FFC673 (games_id), INDEX IDX_C6E727D793855763 (gamescat_id), PRIMARY KEY(games_id, gamescat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games_user (games_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_92EB1A9097FFC673 (games_id), INDEX IDX_92EB1A90A76ED395 (user_id), PRIMARY KEY(games_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gamescat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promos (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, datefin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, idgame_id INT DEFAULT NULL, note INT NOT NULL, INDEX IDX_D8892622786A81FB (iduser_id), INDEX IDX_D88926223B8B8B6B (idgame_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B31CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE games_gamescat ADD CONSTRAINT FK_C6E727D797FFC673 FOREIGN KEY (games_id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_gamescat ADD CONSTRAINT FK_C6E727D793855763 FOREIGN KEY (gamescat_id) REFERENCES gamescat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_user ADD CONSTRAINT FK_92EB1A9097FFC673 FOREIGN KEY (games_id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE games_user ADD CONSTRAINT FK_92EB1A90A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926223B8B8B6B FOREIGN KEY (idgame_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE games_gamescat DROP FOREIGN KEY FK_C6E727D797FFC673');
        $this->addSql('ALTER TABLE games_user DROP FOREIGN KEY FK_92EB1A9097FFC673');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926223B8B8B6B');
        $this->addSql('ALTER TABLE games_gamescat DROP FOREIGN KEY FK_C6E727D793855763');
        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B31CAA392D2');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE games_gamescat');
        $this->addSql('DROP TABLE games_user');
        $this->addSql('DROP TABLE gamescat');
        $this->addSql('DROP TABLE promos');
        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
