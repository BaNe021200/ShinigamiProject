<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108095515 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_staff ADD nick_name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD birthday DATETIME DEFAULT NULL, ADD password VARCHAR(64) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD postal_code VARCHAR(5) DEFAULT NULL, ADD lastname VARCHAR(255) NOT NULL, ADD city VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_staff DROP nick_name, DROP address, DROP phone, DROP birthday, DROP password, DROP email, DROP postal_code, DROP lastname, DROP city');
    }
}
