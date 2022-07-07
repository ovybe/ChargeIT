<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707151651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_carses');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_carses (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, car_id VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_D2100D40A76ED395 (user_id), INDEX IDX_D2100D40C3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_carses ADD CONSTRAINT FK_D2100D40C3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
        $this->addSql('ALTER TABLE users_carses ADD CONSTRAINT FK_D2100D40A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }
}
