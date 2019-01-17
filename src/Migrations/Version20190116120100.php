<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116120100 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_player DROP FOREIGN KEY FK_48A76E92DC555177');
        $this->addSql('DROP INDEX IDX_48A76E92DC555177 ON shini_player');
        $this->addSql('ALTER TABLE shini_player DROP cards_id');
        $this->addSql('ALTER TABLE shini_card ADD player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_card ADD CONSTRAINT FK_AF8AB83E99E6F5DF FOREIGN KEY (player_id) REFERENCES shini_player (id)');
        $this->addSql('CREATE INDEX IDX_AF8AB83E99E6F5DF ON shini_card (player_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_card DROP FOREIGN KEY FK_AF8AB83E99E6F5DF');
        $this->addSql('DROP INDEX IDX_AF8AB83E99E6F5DF ON shini_card');
        $this->addSql('ALTER TABLE shini_card DROP player_id');
        $this->addSql('ALTER TABLE shini_player ADD cards_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_player ADD CONSTRAINT FK_48A76E92DC555177 FOREIGN KEY (cards_id) REFERENCES shini_card (id)');
        $this->addSql('CREATE INDEX IDX_48A76E92DC555177 ON shini_player (cards_id)');
    }
}
