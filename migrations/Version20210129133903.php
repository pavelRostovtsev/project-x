<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210129133903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users DROP FOREIGN KEY FK_4520C24DA76ED395');
        $this->addSql('ALTER TABLE groups_users DROP FOREIGN KEY FK_4520C24DEFB9C8A5');
        $this->addSql('DROP INDEX IDX_4520C24DA76ED395 ON groups_users');
        $this->addSql('DROP INDEX IDX_4520C24DEFB9C8A5 ON groups_users');
        $this->addSql('ALTER TABLE groups_users DROP user_id, DROP association_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groups_users ADD user_id INT NOT NULL, ADD association_id INT NOT NULL');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE groups_users ADD CONSTRAINT FK_4520C24DEFB9C8A5 FOREIGN KEY (association_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4520C24DA76ED395 ON groups_users (user_id)');
        $this->addSql('CREATE INDEX IDX_4520C24DEFB9C8A5 ON groups_users (association_id)');
    }
}
