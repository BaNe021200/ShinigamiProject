<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190124112847 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_player DROP FOREIGN KEY FK_48A76E929B6B5FBA');
        $this->addSql('ALTER TABLE shini_player ADD CONSTRAINT FK_48A76E929B6B5FBA FOREIGN KEY (account_id) REFERENCES shini_player_account (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE shini_player_account DROP FOREIGN KEY FK_D3C8013399E6F5DF');
        $this->addSql('ALTER TABLE shini_player_account ADD CONSTRAINT FK_D3C8013399E6F5DF FOREIGN KEY (player_id) REFERENCES shini_player (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_player DROP FOREIGN KEY FK_48A76E929B6B5FBA');
        $this->addSql('ALTER TABLE shini_player ADD CONSTRAINT FK_48A76E929B6B5FBA FOREIGN KEY (account_id) REFERENCES shini_player_account (id)');
        $this->addSql('ALTER TABLE shini_player_account DROP FOREIGN KEY FK_D3C8013399E6F5DF');
        $this->addSql('ALTER TABLE shini_player_account ADD CONSTRAINT FK_D3C8013399E6F5DF FOREIGN KEY (player_id) REFERENCES shini_player (id)');
    }
}
