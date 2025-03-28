<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327212051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE compagnie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE passager (id INT AUTO_INCREMENT NOT NULL, vole_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, reservation VARCHAR(255) NOT NULL, INDEX IDX_BFF42EE9224C06BF (vole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, vole_id INT DEFAULT NULL, date_reservation DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_42C84955224C06BF (vole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE vole (id INT AUTO_INCREMENT NOT NULL, compagnie_id INT DEFAULT NULL, num INT NOT NULL, date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', heure_depart DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', heure_arrivee DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_D80B1D3D52FBE437 (compagnie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE passager ADD CONSTRAINT FK_BFF42EE9224C06BF FOREIGN KEY (vole_id) REFERENCES vole (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955224C06BF FOREIGN KEY (vole_id) REFERENCES vole (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vole ADD CONSTRAINT FK_D80B1D3D52FBE437 FOREIGN KEY (compagnie_id) REFERENCES compagnie (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE passager DROP FOREIGN KEY FK_BFF42EE9224C06BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955224C06BF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE vole DROP FOREIGN KEY FK_D80B1D3D52FBE437
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE compagnie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE passager
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE vole
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
