<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220703130845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_cars (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, car_id VARCHAR(10) NOT NULL, INDEX IDX_5A4E531AA76ED395 (user_id), INDEX IDX_5A4E531AC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AC3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_cars');
    }
}
