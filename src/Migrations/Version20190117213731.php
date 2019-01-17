<?php declare(strict_types=1);

    namespace DoctrineMigrations;

    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version20190117213731 extends AbstractMigration
    {
        public function up(Schema $schema): void
        {
            // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('CREATE TABLE received_contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EE81EC61E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE article CHANGE author_id author_id INT NOT NULL');
            $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT NOT NULL');
        }

        public function down(Schema $schema): void
        {
            // this down() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('DROP TABLE received_contact');
            $this->addSql('ALTER TABLE article CHANGE author_id author_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE comment CHANGE article_id article_id INT DEFAULT NULL');
        }
    }
