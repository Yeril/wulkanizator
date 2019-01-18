<?php declare(strict_types=1);

    namespace DoctrineMigrations;

    use Doctrine\DBAL\Schema\Schema;
    use Doctrine\Migrations\AbstractMigration;

    /**
     * Auto-generated Migration: Please modify to your needs!
     */
    final class Version20190118134849 extends AbstractMigration
    {
        public function up(Schema $schema): void
        {
            // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('CREATE UNIQUE INDEX UNIQ_743FC3803B8BA7C7 ON curiosity (text)');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_743FC38036AC99F1 ON curiosity (link)');
        }

        public function down(Schema $schema): void
        {
            // this down() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

            $this->addSql('DROP INDEX UNIQ_743FC3803B8BA7C7 ON curiosity');
            $this->addSql('DROP INDEX UNIQ_743FC38036AC99F1 ON curiosity');
        }
    }
