<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200625101752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boutique (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(5) NOT NULL, nom VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, max_client INT DEFAULT NULL, mask_required TINYINT(1) DEFAULT NULL, gel TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_A1223C54A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogue_recompense (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file_attente (id INT AUTO_INCREMENT NOT NULL, boutique_id INT NOT NULL, type VARCHAR(255) NOT NULL, duree TIME NOT NULL, INDEX IDX_4F10E0F2AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_file_attente (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_attente_id INT NOT NULL, heure_entree TIME NOT NULL, heure_sortie TIME NOT NULL, type VARCHAR(255) DEFAULT NULL, affluence INT NOT NULL, INDEX IDX_62CD3755A76ED395 (user_id), INDEX IDX_62CD375593C88149 (file_attente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, catalogue_recompense_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, cout INT NOT NULL, details LONGTEXT DEFAULT NULL, INDEX IDX_1F1B251E85E490E (catalogue_recompense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, item_id INT NOT NULL, date_achat DATE NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_1E9BC0DEA76ED395 (user_id), INDEX IDX_1E9BC0DE126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, points INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE file_attente ADD CONSTRAINT FK_4F10E0F2AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE info_file_attente ADD CONSTRAINT FK_62CD3755A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE info_file_attente ADD CONSTRAINT FK_62CD375593C88149 FOREIGN KEY (file_attente_id) REFERENCES file_attente (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E85E490E FOREIGN KEY (catalogue_recompense_id) REFERENCES catalogue_recompense (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file_attente DROP FOREIGN KEY FK_4F10E0F2AB677BE6');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E85E490E');
        $this->addSql('ALTER TABLE info_file_attente DROP FOREIGN KEY FK_62CD375593C88149');
        $this->addSql('ALTER TABLE recompense DROP FOREIGN KEY FK_1E9BC0DE126F525E');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C54A76ED395');
        $this->addSql('ALTER TABLE info_file_attente DROP FOREIGN KEY FK_62CD3755A76ED395');
        $this->addSql('ALTER TABLE recompense DROP FOREIGN KEY FK_1E9BC0DEA76ED395');
        $this->addSql('DROP TABLE boutique');
        $this->addSql('DROP TABLE catalogue_recompense');
        $this->addSql('DROP TABLE file_attente');
        $this->addSql('DROP TABLE info_file_attente');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE recompense');
        $this->addSql('DROP TABLE user');
    }
}
