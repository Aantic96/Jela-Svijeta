<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230417180457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D43829F712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_tag (food_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F4EE573EBA8E87C4 (food_id), INDEX IDX_F4EE573EBAD26311 (tag_id), PRIMARY KEY(food_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_ingredient (food_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_CEAC8D1BA8E87C4 (food_id), INDEX IDX_CEAC8D1933FE08C (ingredient_id), PRIMARY KEY(food_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE food ADD CONSTRAINT FK_D43829F712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE food_tag ADD CONSTRAINT FK_F4EE573EBA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_tag ADD CONSTRAINT FK_F4EE573EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food DROP FOREIGN KEY FK_D43829F712469DE2');
        $this->addSql('ALTER TABLE food_tag DROP FOREIGN KEY FK_F4EE573EBA8E87C4');
        $this->addSql('ALTER TABLE food_tag DROP FOREIGN KEY FK_F4EE573EBAD26311');
        $this->addSql('ALTER TABLE food_ingredient DROP FOREIGN KEY FK_CEAC8D1BA8E87C4');
        $this->addSql('ALTER TABLE food_ingredient DROP FOREIGN KEY FK_CEAC8D1933FE08C');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE food');
        $this->addSql('DROP TABLE food_tag');
        $this->addSql('DROP TABLE food_ingredient');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
