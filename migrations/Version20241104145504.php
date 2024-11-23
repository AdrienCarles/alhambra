<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104145504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usercommission (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, commission_id INT NOT NULL, isFollowed BOOLEAN SET default FALSE ,UNIQUE INDEX UNIQ_51192DA1A76ED395 (user_id), UNIQUE INDEX UNIQ_51192DA1202D1EB2 (commission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usercommission ADD CONSTRAINT FK_51192DA1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usercommission ADD CONSTRAINT FK_51192DA1202D1EB2 FOREIGN KEY (commission_id) REFERENCES commission (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usercommission DROP FOREIGN KEY FK_51192DA1A76ED395');
        $this->addSql('ALTER TABLE usercommission DROP FOREIGN KEY FK_51192DA1202D1EB2');
        $this->addSql('DROP TABLE usercommission');
    }
}
