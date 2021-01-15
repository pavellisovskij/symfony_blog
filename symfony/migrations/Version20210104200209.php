<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104200209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540594B89032C');
        $this->addSql('DROP INDEX UNIQ_63540594B89032C ON files');
        $this->addSql('ALTER TABLE files DROP post_id');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D93CB796C');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D93CB796C ON post');
        $this->addSql('ALTER TABLE post DROP file_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE files ADD post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540594B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_63540594B89032C ON files (post_id)');
        $this->addSql('ALTER TABLE post ADD file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D93CB796C FOREIGN KEY (file_id) REFERENCES files (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D93CB796C ON post (file_id)');
    }
}
