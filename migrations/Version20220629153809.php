<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629153809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE station (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', location VARCHAR(30) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEF17C53 FOREIGN KEY (plug_id) REFERENCES plug (id)');
        $this->addSql('DROP INDEX IDX_8A28348C21BDB235 ON plug');
        $this->addSql('ALTER TABLE plug DROP station_id');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AA76ED395 FOREIGN KEY (user_id) REFERENCES users (email)');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AC3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE station');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEC3C6F69F');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEF17C53');
        $this->addSql('ALTER TABLE plug ADD station_id VARCHAR(30) NOT NULL');
        $this->addSql('CREATE INDEX IDX_8A28348C21BDB235 ON plug (station_id)');
        $this->addSql('ALTER TABLE users_cars DROP FOREIGN KEY FK_5A4E531AA76ED395');
        $this->addSql('ALTER TABLE users_cars DROP FOREIGN KEY FK_5A4E531AC3C6F69F');
    }
}
