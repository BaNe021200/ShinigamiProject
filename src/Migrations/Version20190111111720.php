<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190111111720 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center ADD center_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_game ADD game_image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD player_image_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE staff ADD staff_image_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_center DROP center_image_name');
        $this->addSql('ALTER TABLE shini_game DROP game_image_name');
        $this->addSql('ALTER TABLE player DROP player_image_name');
        $this->addSql('ALTER TABLE staff DROP staff_image_name');
    }
}
