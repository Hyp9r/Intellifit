<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211028215046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, protein INT DEFAULT NULL, fat INT DEFAULT NULL, carbohydrate INT DEFAULT NULL, calories INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, meal_type VARCHAR(255) NOT NULL, calories INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal_food (meal_id INT NOT NULL, food_id INT NOT NULL, INDEX IDX_CEE6FA03639666D6 (meal_id), UNIQUE INDEX UNIQ_CEE6FA03BA8E87C4 (food_id), PRIMARY KEY(meal_id, food_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:chronos_datetime)\', active TINYINT(1) NOT NULL, phone VARCHAR(35) DEFAULT NULL, token_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:chronos_datetime)\', token VARCHAR(255) DEFAULT NULL, date_of_birth DATE NOT NULL COMMENT \'(DC2Type:chronos_date)\', sex VARCHAR(255) NOT NULL, weight INT NOT NULL, height INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal_food ADD CONSTRAINT FK_CEE6FA03639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal_food ADD CONSTRAINT FK_CEE6FA03BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_food DROP FOREIGN KEY FK_CEE6FA03BA8E87C4');
        $this->addSql('ALTER TABLE meal_food DROP FOREIGN KEY FK_CEE6FA03639666D6');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE meal_food');
        $this->addSql('DROP TABLE user');
    }
}
