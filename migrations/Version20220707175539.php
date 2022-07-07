<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707175539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D494E4F51');
        $this->addSql('DROP INDEX UNIQ_773DE69D494E4F51 ON car');
        $this->addSql('ALTER TABLE car CHANGE carbooking_id booking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D3301C60 ON car (booking_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D3301C60');
        $this->addSql('DROP INDEX UNIQ_773DE69D3301C60 ON car');
        $this->addSql('ALTER TABLE car CHANGE booking_id carbooking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D494E4F51 FOREIGN KEY (carbooking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D494E4F51 ON car (carbooking_id)');
    }
}
