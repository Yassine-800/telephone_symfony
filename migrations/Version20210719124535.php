<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719124535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telephone ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE telephone ADD CONSTRAINT FK_450FF010A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_450FF010A76ED395 ON telephone (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE telephone DROP FOREIGN KEY FK_450FF010A76ED395');
        $this->addSql('DROP INDEX IDX_450FF010A76ED395 ON telephone');
        $this->addSql('ALTER TABLE telephone DROP user_id');
    }
}
