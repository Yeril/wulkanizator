<?php declare(strict_types=1);

    namespace DoctrineMigrations;

    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version20190119215931 extends AbstractMigration
    {
        public function up(Schema $schema): void
        {
            // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(100) NOT NULL, content LONGTEXT DEFAULT NULL, published_at DATETIME DEFAULT NULL, heart_count INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article_id INT DEFAULT NULL, content LONGTEXT NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE curiosity (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_743FC3802B36786B (title), UNIQUE INDEX UNIQ_743FC3803B8BA7C7 (text), UNIQUE INDEX UNIQ_743FC38036AC99F1 (link), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE received_contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, content VARCHAR(500) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_EE81EC61E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_389B783989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE tag_article (tag_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_300B23CCBAD26311 (tag_id), INDEX IDX_300B23CC7294869C (article_id), PRIMARY KEY(tag_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, photo_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, agreed_terms_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6497E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_photo (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE SET NULL');
            $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
            $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE SET NULL');
            $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CCBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CC7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E9E4C8C FOREIGN KEY (photo_id) REFERENCES user_photo (id)');
        }

        public function down(Schema $schema): void
        {
            // this down() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
            $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CC7294869C');
            $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CCBAD26311');
            $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
            $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
            $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497E9E4C8C');
            $this->addSql('DROP TABLE article');
            $this->addSql('DROP TABLE comment');
            $this->addSql('DROP TABLE curiosity');
            $this->addSql('DROP TABLE received_contact');
            $this->addSql('DROP TABLE tag');
            $this->addSql('DROP TABLE tag_article');
            $this->addSql('DROP TABLE user');
            $this->addSql('DROP TABLE user_photo');
        }
    }
