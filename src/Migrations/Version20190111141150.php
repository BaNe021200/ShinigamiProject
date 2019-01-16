<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190111141150 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_game ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_offer ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_player ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_staff ADD image_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center DROP image_name');
        $this->addSql('ALTER TABLE shini_game DROP image_name');
        $this->addSql('ALTER TABLE shini_offer DROP image_name');
        $this->addSql('ALTER TABLE shini_player DROP image_name');
        $this->addSql('ALTER TABLE shini_staff DROP image_name');
    }
}
