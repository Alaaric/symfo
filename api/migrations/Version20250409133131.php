<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409133131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE stat (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, week VARCHAR(255) NOT NULL, views INT DEFAULT NULL, INDEX IDX_20B8FF213DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE stat ADD CONSTRAINT FK_20B8FF213DA5256D FOREIGN KEY (image_id) REFERENCES image (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE stat DROP FOREIGN KEY FK_20B8FF213DA5256D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE stat
        SQL);
    }
}
