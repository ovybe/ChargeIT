<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628110845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, car_id VARCHAR(10) NOT NULL, plug_id INT NOT NULL, start_time DATETIME NOT NULL, duration INT NOT NULL, INDEX IDX_E00CEDDEC3C6F69F (car_id), INDEX IDX_E00CEDDEF17C53 (plug_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (plate VARCHAR(10) NOT NULL, plug_type VARCHAR(10) NOT NULL, PRIMARY KEY(plate)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plug (id INT AUTO_INCREMENT NOT NULL, station_id VARCHAR(30) NOT NULL, status TINYINT(1) NOT NULL, type VARCHAR(10) NOT NULL, max_output NUMERIC(5, 1) NOT NULL, INDEX IDX_8A28348C21BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (location VARCHAR(30) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(location)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (email VARCHAR(100) NOT NULL, name VARCHAR(50) NOT NULL, auth VARCHAR(64) NOT NULL, PRIMARY KEY(email)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_cars (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(100) NOT NULL, car_id VARCHAR(10) NOT NULL, INDEX IDX_5A4E531AA76ED395 (user_id), INDEX IDX_5A4E531AC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEC3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEF17C53 FOREIGN KEY (plug_id) REFERENCES plug (id)');
        $this->addSql('ALTER TABLE plug ADD CONSTRAINT FK_8A28348C21BDB235 FOREIGN KEY (station_id) REFERENCES station (location)');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AA76ED395 FOREIGN KEY (user_id) REFERENCES user (email)');
        $this->addSql('ALTER TABLE users_cars ADD CONSTRAINT FK_5A4E531AC3C6F69F FOREIGN KEY (car_id) REFERENCES car (plate)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEC3C6F69F');
        $this->addSql('ALTER TABLE users_cars DROP FOREIGN KEY FK_5A4E531AC3C6F69F');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEF17C53');
        $this->addSql('ALTER TABLE plug DROP FOREIGN KEY FK_8A28348C21BDB235');
        $this->addSql('ALTER TABLE users_cars DROP FOREIGN KEY FK_5A4E531AA76ED395');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE plug');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_cars');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
