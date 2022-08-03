<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802235837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, origin_id VARCHAR(16) NOT NULL, origin_number INT NOT NULL, origin_code VARCHAR(6) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6956883F5E237E06 (name), INDEX IDX_6956883F56A273CC (origin_id), INDEX IDX_6956883F314A43E9 (origin_number), INDEX IDX_6956883FB03BC868 (origin_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exchange_rate (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, value NUMERIC(10, 4) NOT NULL, date DATE NOT NULL, INDEX IDX_E9521FAB38248176 (currency_id), INDEX IDX_E9521FAB1D775834 (value), INDEX IDX_E9521FABAA9E377A (date), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_rate ADD CONSTRAINT FK_E9521FAB38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange_rate DROP FOREIGN KEY FK_E9521FAB38248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE exchange_rate');
    }
}
