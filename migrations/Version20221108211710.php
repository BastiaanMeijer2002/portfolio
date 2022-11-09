<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108211710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE portfolio_element_tag (portfolio_element_id INTEGER NOT NULL, tag_id INTEGER NOT NULL, PRIMARY KEY(portfolio_element_id, tag_id), CONSTRAINT FK_EA78123F2D39EE56 FOREIGN KEY (portfolio_element_id) REFERENCES portfolio_element (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EA78123FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_EA78123F2D39EE56 ON portfolio_element_tag (portfolio_element_id)');
        $this->addSql('CREATE INDEX IDX_EA78123FBAD26311 ON portfolio_element_tag (tag_id)');
        $this->addSql('CREATE TABLE tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(25) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE portfolio_element_tag');
        $this->addSql('DROP TABLE tag');
    }
}
