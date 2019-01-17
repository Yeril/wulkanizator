<?php declare(strict_types=1);

    namespace DoctrineMigrations;

    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version20190117224404 extends AbstractMigration
    {
        public function up(Schema $schema): void
        {
            // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('ALTER TABLE received_contact ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
        }

        public function down(Schema $schema): void
        {
            // this down() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('ALTER TABLE received_contact DROP created_at, DROP updated_at, CHANGE email email VARCHAR(500) NOT NULL COLLATE utf8mb4_unicode_ci');
        }
    }
