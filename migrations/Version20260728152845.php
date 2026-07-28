<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260728152845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidacy (id INT AUTO_INCREMENT NOT NULL, motivation VARCHAR(255) NOT NULL, cv_file_path VARCHAR(255) NOT NULL, project_links VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, freelance_id INT DEFAULT NULL, client_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, INDEX IDX_D930569DE8DF656B (freelance_id), INDEX IDX_D930569D19EB6921 (client_id), INDEX IDX_D930569DBE6CAE90 (mission_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE candidacy_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE invoice (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, deposit_percentage INT NOT NULL, deposit_amount DOUBLE PRECISION NOT NULL, paid_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, status_id INT DEFAULT NULL, client_id INT DEFAULT NULL, freelance_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, INDEX IDX_906517446BF700BD (status_id), INDEX IDX_9065174419EB6921 (client_id), INDEX IDX_90651744E8DF656B (freelance_id), UNIQUE INDEX UNIQ_90651744BE6CAE90 (mission_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE invoice_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, budget DOUBLE PRECISION NOT NULL, deadline DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, client_id INT DEFAULT NULL, freelance_id INT DEFAULT NULL, status_id INT DEFAULT NULL, language_id INT DEFAULT NULL, INDEX IDX_9067F23C19EB6921 (client_id), INDEX IDX_9067F23CE8DF656B (freelance_id), INDEX IDX_9067F23C6BF700BD (status_id), INDEX IDX_9067F23C82F1BAF4 (language_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mission_category (mission_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_EB01878BE6CAE90 (mission_id), INDEX IDX_EB0187812469DE2 (category_id), PRIMARY KEY (mission_id, category_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE mission_status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569DE8DF656B FOREIGN KEY (freelance_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidacy ADD CONSTRAINT FK_D930569DBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517446BF700BD FOREIGN KEY (status_id) REFERENCES invoice_status (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_9065174419EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744E8DF656B FOREIGN KEY (freelance_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CE8DF656B FOREIGN KEY (freelance_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C6BF700BD FOREIGN KEY (status_id) REFERENCES mission_status (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE mission_category ADD CONSTRAINT FK_EB01878BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_category ADD CONSTRAINT FK_EB0187812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569DE8DF656B');
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569D19EB6921');
        $this->addSql('ALTER TABLE candidacy DROP FOREIGN KEY FK_D930569DBE6CAE90');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_906517446BF700BD');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_9065174419EB6921');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744E8DF656B');
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744BE6CAE90');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C19EB6921');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CE8DF656B');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C6BF700BD');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C82F1BAF4');
        $this->addSql('ALTER TABLE mission_category DROP FOREIGN KEY FK_EB01878BE6CAE90');
        $this->addSql('ALTER TABLE mission_category DROP FOREIGN KEY FK_EB0187812469DE2');
        $this->addSql('DROP TABLE candidacy');
        $this->addSql('DROP TABLE candidacy_status');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE invoice_status');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE mission_category');
        $this->addSql('DROP TABLE mission_status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
