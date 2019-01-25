<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181221111955 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shini_card (id INT AUTO_INCREMENT NOT NULL, center_id INT DEFAULT NULL, rfid INT NOT NULL, qrcode INT DEFAULT NULL, motif INT NOT NULL, INDEX IDX_AF8AB83E5932F377 (center_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_center (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_center_shini_game (shini_center_id INT NOT NULL, shini_game_id INT NOT NULL, INDEX IDX_B7D4D4D3EBCEDF2C (shini_center_id), INDEX IDX_B7D4D4D3C78600AE (shini_game_id), PRIMARY KEY(shini_center_id, shini_game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_game (id INT AUTO_INCREMENT NOT NULL, date_begin DATETIME NOT NULL, date_end DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, staff_adviser_id INT DEFAULT NULL, price DOUBLE PRECISION NOT NULL, date_end DATETIME NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, shown TINYINT(1) NOT NULL, INDEX IDX_F7D487DBCA31A507 (staff_adviser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, cards_id INT DEFAULT NULL, account_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, nick_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, birthday DATETIME NOT NULL, card_code INT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_48A76E92DC555177 (cards_id), UNIQUE INDEX UNIQ_48A76E929B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_player_shini_game (shini_player_id INT NOT NULL, shini_game_id INT NOT NULL, INDEX IDX_CD16AB322B1AD984 (shini_player_id), INDEX IDX_CD16AB32C78600AE (shini_game_id), PRIMARY KEY(shini_player_id, shini_game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_player_account (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_D3C8013399E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shini_player_account_shini_offer (shini_player_account_id INT NOT NULL, shini_offer_id INT NOT NULL, INDEX IDX_4DB23DB8CCC8E5E9 (shini_player_account_id), INDEX IDX_4DB23DB812E10757 (shini_offer_id), PRIMARY KEY(shini_player_account_id, shini_offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shini_card ADD CONSTRAINT FK_AF8AB83E5932F377 FOREIGN KEY (center_id) REFERENCES shini_center (id)');
        $this->addSql('ALTER TABLE shini_center_shini_game ADD CONSTRAINT FK_B7D4D4D3EBCEDF2C FOREIGN KEY (shini_center_id) REFERENCES shini_center (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shini_center_shini_game ADD CONSTRAINT FK_B7D4D4D3C78600AE FOREIGN KEY (shini_game_id) REFERENCES shini_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_F7D487DBCA31A507 FOREIGN KEY (staff_adviser_id) REFERENCES staff (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_48A76E92DC555177 FOREIGN KEY (cards_id) REFERENCES shini_card (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_48A76E929B6B5FBA FOREIGN KEY (account_id) REFERENCES shini_player_account (id)');
        $this->addSql('ALTER TABLE shini_player_shini_game ADD CONSTRAINT FK_CD16AB322B1AD984 FOREIGN KEY (shini_player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shini_player_shini_game ADD CONSTRAINT FK_CD16AB32C78600AE FOREIGN KEY (shini_game_id) REFERENCES shini_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shini_player_account ADD CONSTRAINT FK_D3C8013399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE shini_player_account_shini_offer ADD CONSTRAINT FK_4DB23DB8CCC8E5E9 FOREIGN KEY (shini_player_account_id) REFERENCES shini_player_account (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shini_player_account_shini_offer ADD CONSTRAINT FK_4DB23DB812E10757 FOREIGN KEY (shini_offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_48A76E92DC555177');
        $this->addSql('ALTER TABLE shini_card DROP FOREIGN KEY FK_AF8AB83E5932F377');
        $this->addSql('ALTER TABLE shini_center_shini_game DROP FOREIGN KEY FK_B7D4D4D3EBCEDF2C');
        $this->addSql('ALTER TABLE shini_center_shini_game DROP FOREIGN KEY FK_B7D4D4D3C78600AE');
        $this->addSql('ALTER TABLE shini_player_shini_game DROP FOREIGN KEY FK_CD16AB32C78600AE');
        $this->addSql('ALTER TABLE shini_player_account_shini_offer DROP FOREIGN KEY FK_4DB23DB812E10757');
        $this->addSql('ALTER TABLE shini_player_shini_game DROP FOREIGN KEY FK_CD16AB322B1AD984');
        $this->addSql('ALTER TABLE shini_player_account DROP FOREIGN KEY FK_D3C8013399E6F5DF');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_48A76E929B6B5FBA');
        $this->addSql('ALTER TABLE shini_player_account_shini_offer DROP FOREIGN KEY FK_4DB23DB8CCC8E5E9');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_F7D487DBCA31A507');
        $this->addSql('DROP TABLE shini_card');
        $this->addSql('DROP TABLE shini_center');
        $this->addSql('DROP TABLE shini_center_shini_game');
        $this->addSql('DROP TABLE shini_game');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE shini_player_shini_game');
        $this->addSql('DROP TABLE shini_player_account');
        $this->addSql('DROP TABLE shini_player_account_shini_offer');
        $this->addSql('DROP TABLE staff');
    }
}
