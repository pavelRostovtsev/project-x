<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129084335 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users ADD association_id INT NOT NULL');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DEFB9C8A5 FOREIGN KEY (association_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_4520C24DEFB9C8A5 ON groups_users (association_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users DROP FOREIGN KEY FK_4520C24DEFB9C8A5');
        $this->addSql('DROP INDEX IDX_4520C24DEFB9C8A5 ON groups_users');
        $this->addSql('ALTER TABLE groups_users DROP association_id');
    }
}
