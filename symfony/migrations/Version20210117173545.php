<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117173545 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DCDE46FDB');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8DCDE46FDB ON post');
        $this->addSql('ALTER TABLE post CHANGE preview_id file_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D93CB796C ON post (file_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D93CB796C');
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D93CB796C ON post');
        $this->addSql('ALTER TABLE post CHANGE file_id preview_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DCDE46FDB FOREIGN KEY (preview_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8DCDE46FDB ON post (preview_id)');
    }
}
