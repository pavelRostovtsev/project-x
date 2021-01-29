<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125072604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend ADD user2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61441B8B65 FOREIGN KEY (user2_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_55EEAC61441B8B65 ON friend (user2_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61441B8B65');
        $this->addSql('DROP INDEX IDX_55EEAC61441B8B65 ON friend');
        $this->addSql('ALTER TABLE friend DROP user2_id');
    }
}
