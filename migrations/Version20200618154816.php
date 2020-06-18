<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618154816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(5) NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A1223C54A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_attente (id INT AUTO_INCREMENT NOT NULL, info_file_attente_id INT DEFAULT NULL, boutique_id INT NOT NULL, type VARCHAR(255) NOT NULL, duree TIME NOT NULL, INDEX IDX_4F10E0F279E43730 (info_file_attente_id), INDEX IDX_4F10E0F2AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file_attente ADD CONSTRAINT FK_4F10E0F279E43730 FOREIGN KEY (info_file_attente_id) REFERENCES info_file_attente (id)');
        $this->addSql('ALTER TABLE file_attente ADD CONSTRAINT FK_4F10E0F2AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE info_file_attente ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE info_file_attente ADD CONSTRAINT FK_62CD3755A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_62CD3755A76ED395 ON info_file_attente (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_attente DROP FOREIGN KEY FK_4F10E0F2AB677BE6');
        $this->addSql('DROP TABLE boutique');
        $this->addSql('DROP TABLE file_attente');
        $this->addSql('ALTER TABLE info_file_attente DROP FOREIGN KEY FK_62CD3755A76ED395');
        $this->addSql('DROP INDEX IDX_62CD3755A76ED395 ON info_file_attente');
        $this->addSql('ALTER TABLE info_file_attente DROP user_id');
    }
}
