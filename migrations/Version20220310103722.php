<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220310103722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, produit_id VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, numtelephone INT NOT NULL, totalcost INT NOT NULL, email VARCHAR(255) NOT NULL, quantite LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', date_commande DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_games (commande_id INT NOT NULL, games_id INT NOT NULL, INDEX IDX_C64BF4F282EA2E54 (commande_id), INDEX IDX_C64BF4F297FFC673 (games_id), PRIMARY KEY(commande_id, games_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_games ADD CONSTRAINT FK_C64BF4F282EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande_games ADD CONSTRAINT FK_C64BF4F297FFC673 FOREIGN KEY (games_id) REFERENCES games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_games DROP FOREIGN KEY FK_C64BF4F282EA2E54');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_games');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
