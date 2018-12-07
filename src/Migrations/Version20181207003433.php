<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181207003433 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E213B7323CB');
        $this->addSql('DROP TABLE phones');
        $this->addSql('DROP INDEX IDX_62534E213B7323CB ON customers');
        $this->addSql('ALTER TABLE customers ADD phone CHAR(12), DROP phone_id');
        $this->addSql('ALTER TABLE users ADD phone CHAR(12)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phones (id INT AUTO_INCREMENT NOT NULL, ddd CHAR(2) DEFAULT NULL COLLATE utf8mb4_unicode_ci, number CHAR(9) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customers ADD phone_id INT NOT NULL, DROP phone');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E213B7323CB FOREIGN KEY (phone_id) REFERENCES phones (id)');
        $this->addSql('CREATE INDEX IDX_62534E213B7323CB ON customers (phone_id)');
        $this->addSql('ALTER TABLE users DROP phone');
    }
}
