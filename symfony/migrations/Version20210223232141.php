<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223232141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD main_page TINYINT(1) NOT NULL, CHANGE sum_of_grades sum_of_grades INT DEFAULT 0 NOT NULL, CHANGE number_of_grades number_of_grades INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP main_page, CHANGE sum_of_grades sum_of_grades INT NOT NULL, CHANGE number_of_grades number_of_grades INT NOT NULL');
    }
}
