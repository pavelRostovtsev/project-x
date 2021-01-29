<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129134312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users ADD user_id INT NOT NULL, ADD public_id INT NOT NULL');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DB5B48B91 FOREIGN KEY (public_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_4520C24DA76ED395 ON groups_users (user_id)');
        $this->addSql('CREATE INDEX IDX_4520C24DB5B48B91 ON groups_users (public_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users DROP FOREIGN KEY FK_4520C24DA76ED395');
        $this->addSql('ALTER TABLE groups_users DROP FOREIGN KEY FK_4520C24DB5B48B91');
        $this->addSql('DROP INDEX IDX_4520C24DA76ED395 ON groups_users');
        $this->addSql('DROP INDEX IDX_4520C24DB5B48B91 ON groups_users');
        $this->addSql('ALTER TABLE groups_users DROP user_id, DROP public_id');
    }
}
