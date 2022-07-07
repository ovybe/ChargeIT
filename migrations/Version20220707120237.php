<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707120237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_carses (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, car_id VARCHAR(10) NOT NULL, INDEX IDX_D2100D40A76ED395 (user_id), INDEX IDX_D2100D40C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_carses ADD CONSTRAINT FK_D2100D40A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_carses ADD CONSTRAINT FK_D2100D40C3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
        $this->addSql('ALTER TABLE car ADD carbooking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D494E4F51 FOREIGN KEY (carbooking_id) REFERENCES booking (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_773DE69D494E4F51 ON car (carbooking_id)');
        $this->addSql('ALTER TABLE users_cars MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE users_cars DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE users_cars DROP id');
        $this->addSql('ALTER TABLE users_cars ADD PRIMARY KEY (user_id, car_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_carses');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D494E4F51');
        $this->addSql('DROP INDEX UNIQ_773DE69D494E4F51 ON car');
        $this->addSql('ALTER TABLE car DROP carbooking_id');
        $this->addSql('ALTER TABLE users_cars ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }
}
