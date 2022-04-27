<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427134634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ban (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, date_fin DATE DEFAULT NULL, reason LONGTEXT DEFAULT NULL, INDEX IDX_62FED0E579F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ban ADD CONSTRAINT FK_62FED0E579F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE categorie_id categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo VARCHAR(50000) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ban');
        $this->addSql('ALTER TABLE client CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE news CHANGE categorie_id categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE photo photo MEDIUMTEXT DEFAULT NULL');
    }
}
