<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190107132816 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_player CHANGE address address VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(20) DEFAULT NULL, CHANGE birthday birthday DATETIME DEFAULT NULL, CHANGE card_code card_code INT DEFAULT NULL, CHANGE postal_code postal_code VARCHAR(5) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_player CHANGE address address VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE phone phone VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE birthday birthday DATETIME NOT NULL, CHANGE card_code card_code INT NOT NULL, CHANGE postal_code postal_code VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE city city VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
