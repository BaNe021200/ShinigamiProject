<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108101451 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_staff ADD center_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shini_staff ADD CONSTRAINT FK_9C6CF3775932F377 FOREIGN KEY (center_id) REFERENCES shini_center (id)');
        $this->addSql('CREATE INDEX IDX_9C6CF3775932F377 ON shini_staff (center_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shini_staff DROP FOREIGN KEY FK_9C6CF3775932F377');
        $this->addSql('DROP INDEX IDX_9C6CF3775932F377 ON shini_staff');
        $this->addSql('ALTER TABLE shini_staff DROP center_id');
    }
}
