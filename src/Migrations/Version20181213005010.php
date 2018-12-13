<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213005010 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users ADD active_status VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customers CHANGE document_number document_number VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customers CHANGE document_number document_number VARCHAR(14) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone phone CHAR(12) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE users DROP active_status, CHANGE password password VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone phone CHAR(12) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
