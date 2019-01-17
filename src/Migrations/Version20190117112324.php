<?php declare(strict_types=1);

    namespace DoctrineMigrations;

    use Doctrine\DBAL\DBALException;
    use Doctrine\DBAL\Migrations\AbortMigrationException;
    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version20190117112324 extends AbstractMigration
    {
        public function up(Schema $schema): void
        {
            // this up() migration is auto-generated, please modify it to your needs
            try {
                $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
            } catch (DBALException $e) {
            } catch (AbortMigrationException $e) {
            }

            $this->addSql('CREATE TABLE user_photo (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE user ADD photo_id INT DEFAULT NULL');
            $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497E9E4C8C FOREIGN KEY (photo_id) REFERENCES user_photo (id)');
            $this->addSql('CREATE INDEX IDX_8D93D6497E9E4C8C ON user (photo_id)');
        }

        public function down(Schema $schema): void
        {
            // this down() migration is auto-generated, please modify it to your needs
            try {
                $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
            } catch (DBALException $e) {
            } catch (AbortMigrationException $e) {
            }

            $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497E9E4C8C');
            $this->addSql('DROP TABLE user_photo');
            $this->addSql('DROP INDEX IDX_8D93D6497E9E4C8C ON user');
            $this->addSql('ALTER TABLE user DROP photo_id');
        }
    }
