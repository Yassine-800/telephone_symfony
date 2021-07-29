<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210717133229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE constructeur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_logo VARCHAR(255) NOT NULL, origin VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, address LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE telephone ADD constructeur_id INT NOT NULL');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF0108815B605 FOREIGN KEY (constructeur_id) REFERENCES constructeur (id)');
        $this->addSql('CREATE INDEX IDX_450FF0108815B605 ON telephone (constructeur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF0108815B605');
        $this->addSql('DROP TABLE constructeur');
        $this->addSql('DROP INDEX IDX_450FF0108815B605 ON telephone');
        $this->addSql('ALTER TABLE telephone DROP constructeur_id');
    }
}
