<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207150735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(2083) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_category (category_source INT NOT NULL, category_target INT NOT NULL, INDEX IDX_B1369DBA5062B508 (category_source), INDEX IDX_B1369DBA4987E587 (category_target), PRIMARY KEY(category_source, category_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, my_object_id INT NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CC57C58BF (my_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(2083) NOT NULL, description LONGTEXT NOT NULL, rating DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, INDEX IDX_BC96361A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_collection_my_object (my_collection_id INT NOT NULL, my_object_id INT NOT NULL, INDEX IDX_45711023833C265C (my_collection_id), INDEX IDX_45711023C57C58BF (my_object_id), PRIMARY KEY(my_collection_id, my_object_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE my_object (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(2083) NOT NULL, description LONGTEXT NOT NULL, state VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_34C86BF712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, picture VARCHAR(2083) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_my_collection (user_id INT NOT NULL, my_collection_id INT NOT NULL, INDEX IDX_E547CE31A76ED395 (user_id), INDEX IDX_E547CE31833C265C (my_collection_id), PRIMARY KEY(user_id, my_collection_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBA5062B508 FOREIGN KEY (category_source) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_category ADD CONSTRAINT FK_B1369DBA4987E587 FOREIGN KEY (category_target) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC57C58BF FOREIGN KEY (my_object_id) REFERENCES my_object (id)');
        $this->addSql('ALTER TABLE my_collection ADD CONSTRAINT FK_BC96361A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE my_collection_my_object ADD CONSTRAINT FK_45711023833C265C FOREIGN KEY (my_collection_id) REFERENCES my_collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE my_collection_my_object ADD CONSTRAINT FK_45711023C57C58BF FOREIGN KEY (my_object_id) REFERENCES my_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE my_object ADD CONSTRAINT FK_34C86BF712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE user_my_collection ADD CONSTRAINT FK_E547CE31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_my_collection ADD CONSTRAINT FK_E547CE31833C265C FOREIGN KEY (my_collection_id) REFERENCES my_collection (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_category DROP FOREIGN KEY FK_B1369DBA5062B508');
        $this->addSql('ALTER TABLE category_category DROP FOREIGN KEY FK_B1369DBA4987E587');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC57C58BF');
        $this->addSql('ALTER TABLE my_collection DROP FOREIGN KEY FK_BC96361A76ED395');
        $this->addSql('ALTER TABLE my_collection_my_object DROP FOREIGN KEY FK_45711023833C265C');
        $this->addSql('ALTER TABLE my_collection_my_object DROP FOREIGN KEY FK_45711023C57C58BF');
        $this->addSql('ALTER TABLE my_object DROP FOREIGN KEY FK_34C86BF712469DE2');
        $this->addSql('ALTER TABLE user_my_collection DROP FOREIGN KEY FK_E547CE31A76ED395');
        $this->addSql('ALTER TABLE user_my_collection DROP FOREIGN KEY FK_E547CE31833C265C');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE my_collection');
        $this->addSql('DROP TABLE my_collection_my_object');
        $this->addSql('DROP TABLE my_object');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_my_collection');
    }
}
