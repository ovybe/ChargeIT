<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629154431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plug CHANGE station_id station_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE plug ADD CONSTRAINT FK_8A28348C21BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('CREATE INDEX IDX_8A28348C21BDB235 ON plug (station_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plug DROP FOREIGN KEY FK_8A28348C21BDB235');
        $this->addSql('DROP INDEX IDX_8A28348C21BDB235 ON plug');
        $this->addSql('ALTER TABLE plug CHANGE station_id station_id VARCHAR(30) NOT NULL');
    }
}
