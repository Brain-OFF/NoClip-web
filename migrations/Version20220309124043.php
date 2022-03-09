<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220309124043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription_t (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT NOT NULL, user_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, etat TINYINT(1) NOT NULL, rank VARCHAR(255) NOT NULL, INDEX IDX_A82E8D61F607770A (tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription_t ADD CONSTRAINT FK_A82E8D61F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE tournoi ADD nom VARCHAR(255) NOT NULL, ADD date DATETIME NOT NULL, ADD cathegorie VARCHAR(255) NOT NULL, ADD discription LONGTEXT NOT NULL, DROP id_t');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE inscription_t');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournoi ADD id_t INT NOT NULL, DROP nom, DROP date, DROP cathegorie, DROP discription');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
