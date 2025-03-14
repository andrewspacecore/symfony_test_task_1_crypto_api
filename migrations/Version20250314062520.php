<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250314062520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE crypto_price_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cryptocurrency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fiat_currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE crypto_price (id INT NOT NULL, cryptocurrency_id INT DEFAULT NULL, fiat_currency_id INT DEFAULT NULL, price NUMERIC(30, 10) NOT NULL, recorded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC7E32A2583FC03A ON crypto_price (cryptocurrency_id)');
        $this->addSql('CREATE INDEX IDX_DC7E32A2C4F47010 ON crypto_price (fiat_currency_id)');
        $this->addSql('CREATE TABLE cryptocurrency (id INT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC62CFAD77153098 ON cryptocurrency (code)');
        $this->addSql('CREATE TABLE fiat_currency (id INT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBFEA07377153098 ON fiat_currency (code)');
        $this->addSql('ALTER TABLE crypto_price ADD CONSTRAINT FK_DC7E32A2583FC03A FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrency (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crypto_price ADD CONSTRAINT FK_DC7E32A2C4F47010 FOREIGN KEY (fiat_currency_id) REFERENCES fiat_currency (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE crypto_price_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cryptocurrency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fiat_currency_id_seq CASCADE');
        $this->addSql('ALTER TABLE crypto_price DROP CONSTRAINT FK_DC7E32A2583FC03A');
        $this->addSql('ALTER TABLE crypto_price DROP CONSTRAINT FK_DC7E32A2C4F47010');
        $this->addSql('DROP TABLE crypto_price');
        $this->addSql('DROP TABLE cryptocurrency');
        $this->addSql('DROP TABLE fiat_currency');
    }
}
