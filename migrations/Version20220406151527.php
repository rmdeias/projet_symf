<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220406151527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD author VARCHAR(255) NOT NULL, ADD text LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD title VARCHAR(255) DEFAULT NULL, ADD content VARCHAR(255) DEFAULT NULL, ADD date_publication DATETIME NOT NULL, ADD slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP name');
        $this->addSql('ALTER TABLE comment DROP author, DROP text');
        $this->addSql('ALTER TABLE post DROP title, DROP content, DROP date_publication, DROP slug');
    }
}
