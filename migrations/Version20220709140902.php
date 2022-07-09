<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709140902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP INDEX UNIQ_E00CEDDEC3C6F69F, ADD INDEX IDX_E00CEDDEC3C6F69F (car_id)');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D3301C60');
        $this->addSql('DROP INDEX UNIQ_773DE69D3301C60 ON car');
        $this->addSql('ALTER TABLE car CHANGE booking_id capacity INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP INDEX IDX_E00CEDDEC3C6F69F, ADD UNIQUE INDEX UNIQ_E00CEDDEC3C6F69F (car_id)');
        $this->addSql('ALTER TABLE car CHANGE capacity booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D3301C60 ON car (booking_id)');
    }
}
