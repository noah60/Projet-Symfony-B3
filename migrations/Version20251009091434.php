<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251009091434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_service (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, ville VARCHAR(100) NOT NULL, code_postal VARCHAR(10) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_C7440455FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_service (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, categorie_service_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(100) NOT NULL, date_creation DATETIME NOT NULL, status VARCHAR(50) NOT NULL, localisation VARCHAR(255) NOT NULL, image_filename VARCHAR(255) DEFAULT NULL, INDEX IDX_D16A217D19EB6921 (client_id), INDEX IDX_D16A217D7395634A (categorie_service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestataire (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, competences LONGTEXT NOT NULL, tarif_horaire NUMERIC(10, 2) NOT NULL, biographie LONGTEXT NOT NULL, statutdisponible VARCHAR(50) NOT NULL, nombreavis INT NOT NULL, note NUMERIC(3, 2) NOT NULL, INDEX IDX_60A26480FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collaborer (prestataire_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_2339265EBE3DB2B7 (prestataire_id), INDEX IDX_2339265E19EB6921 (client_id), PRIMARY KEY(prestataire_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande (prestataire_id INT NOT NULL, demande_service_id INT NOT NULL, INDEX IDX_2694D7A5BE3DB2B7 (prestataire_id), INDEX IDX_2694D7A51B90A347 (demande_service_id), PRIMARY KEY(prestataire_id, demande_service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE demande_service ADD CONSTRAINT FK_D16A217D7395634A FOREIGN KEY (categorie_service_id) REFERENCES categorie_service (id)');
        $this->addSql('ALTER TABLE prestataire ADD CONSTRAINT FK_60A26480FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE collaborer ADD CONSTRAINT FK_2339265EBE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collaborer ADD CONSTRAINT FK_2339265E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A5BE3DB2B7 FOREIGN KEY (prestataire_id) REFERENCES prestataire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande ADD CONSTRAINT FK_2694D7A51B90A347 FOREIGN KEY (demande_service_id) REFERENCES demande_service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455FB88E14F');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217D19EB6921');
        $this->addSql('ALTER TABLE demande_service DROP FOREIGN KEY FK_D16A217D7395634A');
        $this->addSql('ALTER TABLE prestataire DROP FOREIGN KEY FK_60A26480FB88E14F');
        $this->addSql('ALTER TABLE collaborer DROP FOREIGN KEY FK_2339265EBE3DB2B7');
        $this->addSql('ALTER TABLE collaborer DROP FOREIGN KEY FK_2339265E19EB6921');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A5BE3DB2B7');
        $this->addSql('ALTER TABLE demande DROP FOREIGN KEY FK_2694D7A51B90A347');
        $this->addSql('DROP TABLE categorie_service');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE demande_service');
        $this->addSql('DROP TABLE prestataire');
        $this->addSql('DROP TABLE collaborer');
        $this->addSql('DROP TABLE demande');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
