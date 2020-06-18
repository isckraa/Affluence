<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618155512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogue_recompense (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, catalogue_recompense_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, cout INT NOT NULL, details LONGTEXT DEFAULT NULL, INDEX IDX_1F1B251E85E490E (catalogue_recompense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recompense (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, item_id INT NOT NULL, date_achat DATE NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_1E9BC0DEA76ED395 (user_id), INDEX IDX_1E9BC0DE126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E85E490E FOREIGN KEY (catalogue_recompense_id) REFERENCES catalogue_recompense (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recompense ADD CONSTRAINT FK_1E9BC0DE126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E85E490E');
        $this->addSql('ALTER TABLE recompense DROP FOREIGN KEY FK_1E9BC0DE126F525E');
        $this->addSql('DROP TABLE catalogue_recompense');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE recompense');
    }
}
