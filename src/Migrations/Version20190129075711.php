<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190129075711 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center ADD name VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE shini_offer DROP image, CHANGE shown shown TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE onfirstpage onfirstpage TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center DROP name, DROP description');
        $this->addSql('ALTER TABLE shini_offer ADD image VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE onfirstpage onfirstpage TINYINT(1) NOT NULL, CHANGE shown shown TINYINT(1) NOT NULL');
    }
}
