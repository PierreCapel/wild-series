<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210528200342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778416EB9F66');
        // $this->addSql('DROP INDEX IDX_92ED778416EB9F66 ON program');
        // $this->addSql('ALTER TABLE program DROP seasons_id');
        // $this->addSql('ALTER TABLE season ADD programs_id INT NOT NULL');
        // $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA979AEC3C FOREIGN KEY (programs_id) REFERENCES program (id)');
        // $this->addSql('CREATE INDEX IDX_F0E45BA979AEC3C ON season (programs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program ADD seasons_id INT NOT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778416EB9F66 FOREIGN KEY (seasons_id) REFERENCES season (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_92ED778416EB9F66 ON program (seasons_id)');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA979AEC3C');
        $this->addSql('DROP INDEX IDX_F0E45BA979AEC3C ON season');
        $this->addSql('ALTER TABLE season DROP programs_id');
    }
}
