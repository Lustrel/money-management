<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190101231936 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE installments DROP FOREIGN KEY FK_FE90068C6BF700BD');
        $this->addSql('ALTER TABLE loans DROP FOREIGN KEY FK_82C24DBC3FB46D09');
        $this->addSql('ALTER TABLE users CHANGE role_id role_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE roles CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE installments CHANGE status_id status_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE installment_periods CHANGE id id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE loans CHANGE installment_period_id installment_period_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE installment_status CHANGE id id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE installment_periods CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE installment_status CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE installments CHANGE status_id status_id INT NOT NULL');
        $this->addSql('ALTER TABLE loans CHANGE installment_period_id installment_period_id INT NOT NULL');
        $this->addSql('ALTER TABLE roles CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE role_id role_id INT NOT NULL');
        $this->addSql('ALTER TABLE installments ADD CONSTRAINT FK_FE90068C6BF700BD FOREIGN KEY (status_id) REFERENCES installment_status (id)');
        $this->addSql('ALTER TABLE loans ADD CONSTRAINT FK_82C24DBC3FB46D09 FOREIGN KEY (installment_period_id) REFERENCES installment_periods (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
    }
}
