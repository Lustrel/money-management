<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181215142637 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, document_number VARCHAR(14) NOT NULL, email VARCHAR(255) NOT NULL, phone CHAR(12), INDEX IDX_62534E21A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installments (id INT AUTO_INCREMENT NOT NULL, loan_id INT NOT NULL, status_id INT NOT NULL, due_date DATE NOT NULL, value INT NOT NULL, INDEX IDX_FE90068CCE73868F (loan_id), INDEX IDX_FE90068C6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installment_periods (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE installment_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE loans (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, installment_period_id INT NOT NULL, borrowed_value INT NOT NULL, total_installments INT NOT NULL, monthly_fee NUMERIC(5, 2) NOT NULL, discount NUMERIC(5, 2) NOT NULL, comments LONGTEXT NOT NULL, INDEX IDX_82C24DBC9395C3F3 (customer_id), INDEX IDX_82C24DBC3FB46D09 (installment_period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, phone CHAR(12), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customers ADD CONSTRAINT FK_62534E21A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE installments ADD CONSTRAINT FK_FE90068CCE73868F FOREIGN KEY (loan_id) REFERENCES loans (id)');
        $this->addSql('ALTER TABLE installments ADD CONSTRAINT FK_FE90068C6BF700BD FOREIGN KEY (status_id) REFERENCES installment_status (id)');
        $this->addSql('ALTER TABLE loans ADD CONSTRAINT FK_82C24DBC9395C3F3 FOREIGN KEY (customer_id) REFERENCES customers (id)');
        $this->addSql('ALTER TABLE loans ADD CONSTRAINT FK_82C24DBC3FB46D09 FOREIGN KEY (installment_period_id) REFERENCES installment_periods (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE loans DROP FOREIGN KEY FK_82C24DBC9395C3F3');
        $this->addSql('ALTER TABLE loans DROP FOREIGN KEY FK_82C24DBC3FB46D09');
        $this->addSql('ALTER TABLE installments DROP FOREIGN KEY FK_FE90068C6BF700BD');
        $this->addSql('ALTER TABLE installments DROP FOREIGN KEY FK_FE90068CCE73868F');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE customers DROP FOREIGN KEY FK_62534E21A76ED395');
        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE installments');
        $this->addSql('DROP TABLE installment_periods');
        $this->addSql('DROP TABLE installment_status');
        $this->addSql('DROP TABLE loans');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE users');
    }
}
